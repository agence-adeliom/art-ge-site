import React, {useEffect, useState} from "react"
import Filters from '@components/Filters/Filters';
import { Heading } from '@components/Typography/Heading';
import Header from '@components/Territory/Header'
import ScoreTerritory from "@components/Territory/ScoreTerritory";
import SustainabiltiesScores from "@components/Territory/SustainabiltiesScores";
import ActorsScores from "@components/Territory/ActorsScores";
import Analysis from "@components/Territory/Analysis";
import FooterResult from "@components/Navigation/FooterResults";
import Tabs from "@components/Territory/Tabs";
import { useParams } from "react-router-dom";
import NoDataModal from "@components/Modal/NoDataModal";

export type Sluggable = { slug: string, name: string };

export type SelectedTerritoires = Record<string, string[]>;

export interface Thematique {
    avg_points: string;
    avg_total: string;
    name: string;
    score: string;
    slug: string;
}

export type Thematiques = Thematique[];

const Territory = () => {
    const { territoire = 'grand-est' } = useParams();    

    //Global data
    const [territoryScore, setTerritoryScore] = useState(0)
    const [respondantsTotal, setRespondantsTotal] = useState(0)
    const [environnementScore, setEnvironnementScore] = useState(0.01)
    const [economyScore, setEconomyScore] = useState(0.01)
    const [socialScore, setSocialScore] = useState(0.01)
    const [lastSubmission, setLastSubmission] = useState('')
    const [thematiques, setThematiques] = useState<Thematiques>([])

    //Filters
    const [filters, setFilters] = useState()
    const [ot, setOt] = useState<Sluggable[]>([])
    const [typologies, setTypologies] = useState<Sluggable[]>([])
    const [territories, setTerritories] = useState<Sluggable[]>([])
    const [departments, setDepartments] = useState<Sluggable[]>([])
    
    const [selectedTerritoires, setSelectedTerritoires] = useState<SelectedTerritoires>({departments: [], ots: [], tourisms: [], typologies: []})

    // no data to display
    const [openErrorPopin, setOpenErrorPopin] = useState(false);

    const apiFilter = () => {
        fetch(`https://art-grand-est.ddev.site/api/dashboard/${territoire}/filters`)
            .then(response => response.json())
            .then(data => {
                setFilters(data.data);
                setOt(data.data.ots);
                setTypologies(data.data.typologies)
                setTerritories(data.data.tourisms)
                setDepartments(data.data.departments)
        });
    }

    const getSearchParamsFromTerritories = (): string => {
        const params: string[][] = [];
        for (const [key, value] of Object.entries(selectedTerritoires)) {
            if (Array.isArray(value)){    
                for(const v of value) {         
                    params.push([key + '[]', v]);
                }
            }
        }
        return new URLSearchParams(params).toString();
    }

    const apiData = () => {
        const search = getSearchParamsFromTerritories();
        
        fetch(`https://art-grand-est.ddev.site/api/dashboard/${territoire}/data?${search}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    setOpenErrorPopin(true);
                } else {
                    console.log(data.data)
                    setTerritoryScore(data.data.globals.score)
                    setRespondantsTotal(data.data.globals.repondantsCount)
                    setEnvironnementScore(data.data.globals.piliers.environnement)
                    setEconomyScore(data.data.globals.piliers.economie)
                    setSocialScore(data.data.globals.piliers.social)
                    setLastSubmission(data.data.globals.lastSubmission)
                    setThematiques(data.data.scores.thematiques)
                }
        });
    }

    useEffect(() => {
        apiFilter();
        apiData();
    }, [])

    // useEffect(() => {
    //     console.log(filters)
    // }, [filters])


    return (
        <div className="flex">
            <div className="print:hidden z-10 w-[320px] h-screen top-0 sticky py-16 px-10 shadow-[0_2px_4px_4px_rgba(113,113,122,0.12)] flex-shrink-0">
                <Filters
                    filters={filters}
                    apiData={apiData}
                    ot={ot}
                    etablishment={typologies}
                    territories={territories}
                    departments={departments}
                    lastSubmission={lastSubmission}
                    setSelectedTerritoires={setSelectedTerritoires}
                    selectedTerritoires={selectedTerritoires}
                ></Filters>
            </div>
            <div className="w-full">
                <Header></Header>
                <ScoreTerritory
                    territoryScore={territoryScore}
                    respondantsTotal={respondantsTotal}
                />
                <SustainabiltiesScores 
                    environnementScore={environnementScore}
                    economyScore={economyScore}
                    socialScore={socialScore}
                />

                <ActorsScores
                    
                />

                <div className="print:bg-white bg-neutral-50 p-10 pt-12 pb-0">
                    <Heading variant={'display-4'}>Pour aller plus loin dans l’analyse</Heading>
                </div>
                <Analysis
                    icon="fa-thin fa-leaf"
                    type="Environnement" 
                    color="primary-800"
                    barColor="#75B369"
                    percentage={environnementScore}
                    desc="Ci-dessous les résultats détaillés pour chaque thématique liée à l’environnement. <br/>
                    Elle regroupe le respect et la protection de la nature, de la biodiversité ainsi que la réduction de l’impact environnemental."
                    thematiques={thematiques.slice(0,8)}
                ></Analysis>

                <Analysis 
                    icon="fa-thin fa-coins"
                    type="Economie" 
                    color="secondary-800"
                    barColor="#60A5AB"
                    percentage={economyScore}
                    desc="Les graphiques décrivent les résultats pour chaque thématique liée à l’économie. <br />
                    Elle évoque le vivre et consommer local ; le service de proximité, de qualité avec des acteurs vertueux."
                    thematiques={thematiques.slice(8,11)}
                ></Analysis>

                <Analysis 
                    icon="fa-thin fa-people-group"
                    type="Social" 
                    color="tertiary-800"
                    barColor="#75B369"
                    percentage={socialScore}
                    desc="Ci-dessous les résultats détaillés pour chaque thématique liée à l’environnement. 
                    Elle regroupe le respect et la protection de la nature, de la biodiversité ainsi que la réduction de l’impact environnemental."
                    thematiques={thematiques.slice(11,-1)}
                ></Analysis>
                <Tabs></Tabs>
                <FooterResult></FooterResult>
                {openErrorPopin && <NoDataModal closeModal={() => setOpenErrorPopin(false)}></NoDataModal>}
            </div> 
        </div>
    )
}

export default Territory