import React, {useState} from "react"
import Filters from '@components/Filters/Filters';
import { Heading } from '@components/Typography/Heading';
import Header from '@components/Territory/Header'
import ScoreTerritory from "@components/Territory/ScoreTerritory";
import SustainabiltiesScores from "@components/Territory/SustainabiltiesScores";
import ActorsScores from "@components/Territory/ActorsScores";
import Analysis from "@components/Territory/Analysis";
import FooterResult from "@components/Navigation/FooterResults";

const Territory = () => {
    const [territoryScore, setTerritoryScore] = useState(42)
    const [respondantsTotal, setRespondantsTotal] = useState(342)
    
    const [environnementScore, setEnvironnementScore] = useState(39)

    return (
        <div className="flex">
            <div className="print:hidden w-[320px] h-screen top-0 sticky py-16 px-10 shadow-[0_2px_4px_4px_rgba(113,113,122,0.12)] flex-shrink-0">
                <Filters
                    setTerritoryScore={setTerritoryScore}
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
                />

                <ActorsScores
                    
                />

                <div className="bg-neutral-50 p-10 pt-12 pb-0">
                    <Heading variant={'display-4'}>Pour aller plus loin dans l’analyse</Heading>
                </div>
                <Analysis
                    icon="fa-thin fa-leaf"
                    type="Environnement" 
                    color="primary-800"
                    barColor="#75B369"
                    percentage={39}
                    desc="Ci-dessous les résultats détaillés pour chaque thématique liée à l’environnement. <br/>
                    Elle regroupe le respect et la protection de la nature, de la biodiversité ainsi que la réduction de l’impact environnemental."
                ></Analysis>

                <Analysis 
                    icon="fa-thin fa-coins"
                    type="Economie" 
                    color="secondary-800"
                    barColor="#60A5AB"
                    percentage={44}
                    desc="Les graphiques décrivent les résultats pour chaque thématique liée à l’économie. <br />
                    Elle évoque le vivre et consommer local ; le service de proximité, de qualité avec des acteurs vertueux."
                ></Analysis>

                <Analysis 
                    icon="fa-thin fa-people-group"
                    type="Social" 
                    color="tertiary-800"
                    barColor="#75B369"
                    percentage={32}
                    desc="Ci-dessous les résultats détaillés pour chaque thématique liée à l’environnement. 
                    Elle regroupe le respect et la protection de la nature, de la biodiversité ainsi que la réduction de l’impact environnemental."
                ></Analysis>
                <FooterResult></FooterResult>
            </div> 
        </div>
    )
}

export default Territory