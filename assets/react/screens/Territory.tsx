import React, {useState} from "react"
import Filters from '@components/Filters/Filters';
import { Heading } from '@components/Typography/Heading';
import Header from '@components/Territory/Header'
import ScoreTerritory from "@components/Territory/ScoreTerritory";
import SustainabiltiesScores from "@components/Territory/SustainabiltiesScores";

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
            </div> 
        </div>
    )
}

export default Territory