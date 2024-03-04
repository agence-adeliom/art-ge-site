import React from "react"
import Filters from '@components/Filters/Filters';
import { Heading } from '@components/Typography/Heading';
import Header from '@components/Territory/Header'
import ProgressBarTerritory from "@components/ProgressBar/ProgressBarTerritory";
import { Button } from '@components/Action/Button';

const ScoreTerritory = ({territoryScore, respondantsTotal, } : {
    territoryScore: number,
    respondantsTotal: number
}) => {
    return (
        <div className="print:py-4 py-12 px-4 lg:px-10 bg-white" id="breakAfter">
            <div className="flex justify-between items-center">
                <Heading variant="display-4" weight={400} className="mb-4" >
                    Score du territoire
                </Heading>
                <Button onClick={() => window.print()} size="lg" className="max-lg:hidden print:hidden" >
                    <i className="fa-regular fa-file-lines"></i>
                    Exporter les résultats
                </Button>
            </div>
            <div className="flex gap-2 max-md:flex-wrap items-baseline">
                <div className="flex gap-2 items-baseline">
                    <div className="text-5xl font-title text-black">{territoryScore}</div>
                    <div className="text-3xl text-neutral-600 font-title">/100</div>
                </div>
                <div className="ml-2 text-2xl text-neutral-600 font-title">pour {respondantsTotal} répondant{respondantsTotal > 1 ? 's' : ''}</div>
            </div>      
            <div className="mt-8">
                <ProgressBarTerritory percentage={territoryScore}></ProgressBarTerritory>
            </div>
        </div>
    )
}

export default ScoreTerritory