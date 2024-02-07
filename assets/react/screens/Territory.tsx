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
import { ActorsScoresList, DateRange, Lists, SelectedTerritoires, Sluggable, Thematiques } from "@react/types/Dashboard";
import { Button } from "@components/Action/Button";
import moment from 'moment';
import { DateRangePicker,  } from 'react-dates';
moment.locale('fr-fr');

export const getSearchParamsFromTerritories = (selectedTerritoires: SelectedTerritoires, dateRange: DateRange): string => {
    const params: string[][] = [];
    for (const [key, value] of Object.entries(selectedTerritoires)) {
        if (Array.isArray(value)){
            for(const v of value) {
                params.push([key + '[]', v]);
            }
        }
    }
    if (dateRange.startDate) {
        params.push(['from', moment(dateRange.startDate).format('YYYY-MM-DD')]);
    }
    if (dateRange.endDate) {
        params.push(['to', moment(dateRange.endDate).format('YYYY-MM-DD')]);
    }
    return new URLSearchParams(params).toString();
}

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
    const [lists, setLists] = useState<Lists>({})
    const [actorsScores, setActorsScores] = useState<ActorsScoresList>({
        activite: null,
        camping: null,
        chambre: null,
        hotel: null,
        insolite: null,
        location: null,
        restaurant: null,
        visite: null
    })

    //Filters
    const [ot, setOt] = useState<Sluggable[]>([])
    const [typologies, setTypologies] = useState<Sluggable[]>([])
    const [territories, setTerritories] = useState<Sluggable[]>([])
    const [departments, setDepartments] = useState<Sluggable[]>([])

    const [selectedTerritoires, setSelectedTerritoires] = useState<SelectedTerritoires>({departments: [], ots: [], tourisms: [], typologies: []})

    // date range
    const [dateRange, setDateRange] = useState<{startDate: Date|null, endDate: Date|null}>({startDate: null, endDate: null});
    const [focusedInput, setFocusedInput] = useState(null);

    // no data to display
    const [openErrorPopin, setOpenErrorPopin] = useState(false);

    const apiFilter = () => {
        const search = getSearchParamsFromTerritories(selectedTerritoires, dateRange);

        fetch(`/api/dashboard/${territoire}/filters?${search}`)
            .then(response => response.json())
            .then(data => {
                setOt(data.data.ots);
                setTypologies(data.data.typologies)
                setTerritories(data.data.tourisms)
                setDepartments(data.data.departments)
        });
    }

    const apiData = () => {
        const search = getSearchParamsFromTerritories(selectedTerritoires, dateRange);

        fetch(`/api/dashboard/${territoire}/data?${search}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    setOpenErrorPopin(true);
                } else {
                    //console.log(data.data)
                    setTerritoryScore(data.data.globals.score)
                    setRespondantsTotal(data.data.globals.repondantsCount)
                    setEnvironnementScore(data.data.globals.piliers.environnement)
                    setEconomyScore(data.data.globals.piliers.economie)
                    setSocialScore(data.data.globals.piliers.social)
                    setLastSubmission(data.data.globals.lastSubmission)
                    setThematiques(data.data.scores.thematiques)
                    setActorsScores(data.data.scores.typologies)

                    const lists: Lists = {};
                    lists.repondants = data.data.globals.repondants;
                    lists.departments = data.data.lists.departments ?? [];
                    lists.ots = data.data.lists.ots ?? [];
                    setLists(lists);
                }
        });
    }

    useEffect(() => {
        apiData();
    }, [])

    useEffect(() => {
        apiFilter();
    }, [selectedTerritoires])

    const [filterMobile, setFilterMobile] = useState(false);
    const filterClassPannel = filterMobile ? 'max-md:translate-x-0' : 'max-md:translate-x-full';
    const filterClass = "bg-white z-[1000] w-full fixed top-0 meft-0 trans-default md:block print:hidden z-10 md:w-[320px] h-screen md:sticky py-4 md:py-10 px-4 lg:px-10 shadow-[0_2px_4px_4px_rgba(113,113,122,0.12)] flex-shrink-0"

    return (
        <div className="flex">
            <div className={`${filterClassPannel} ${filterClass} overflow-auto`}>
                <Filters
                    apiData={apiData}
                    apiFilter={apiFilter}
                    ot={ot}
                    etablishment={typologies}
                    territories={territories}
                    departments={departments}
                    lastSubmission={lastSubmission}
                    setSelectedTerritoires={setSelectedTerritoires}
                    selectedTerritoires={selectedTerritoires}
                    setFilterMobile={setFilterMobile}
                    setOt={setOt}
                >
                    <DateRangePicker
                        startDate={dateRange.startDate}
                        startDateId="inputStartDate"
                        endDate={dateRange.endDate}
                        endDateId="inputEndDate"
                        //@ts-ignore
                        onDatesChange={({ startDate, endDate } : { startDate: Date|null, endDate: Date|null }) => setDateRange({startDate, endDate})}
                        focusedInput={focusedInput}
                        onFocusChange={(focusedInput: any) => setFocusedInput(focusedInput)}
                        numberOfMonths={1}
                        startDatePlaceholderText={'Début'}
                        endDatePlaceholderText={'Fin'}
                        customArrowIcon={'-'}
                        openDirection={'up'}
                        isOutsideRange={() => false}
                        customInputIcon={<i className="fa-light fa-calendar text-sm"></i>}
                        inputIconPosition="after"
                        displayFormat="DD/MM/YYYY"
                        appendToBody={true}
                        hideKeyboardShortcutsPanel={true}
                    />
                </Filters>
            </div>
            <div className="w-full overflow-hidden">
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
                    scores={actorsScores}
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
                    selectedTerritoires={selectedTerritoires}
                    dateRange={dateRange}
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
                    selectedTerritoires={selectedTerritoires}
                    dateRange={dateRange}
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
                    selectedTerritoires={selectedTerritoires}
                    dateRange={dateRange}
                ></Analysis>
                <Tabs lists={lists}></Tabs>
                <div className="fixed bottom-0 left-0 bg-white p-4 pt-2 w-full z-[100] md:hidden">
                    <Button variant="secondary" className="w-full " icon="fa-minus" onClick={() => setFilterMobile(true)}>
                        Filtrer les résultats
                    </Button>
                </div>
                <FooterResult></FooterResult>
                {openErrorPopin && <NoDataModal closeModal={() => setOpenErrorPopin(false)}></NoDataModal>}
            </div>
        </div>
    )
}

export default Territory
