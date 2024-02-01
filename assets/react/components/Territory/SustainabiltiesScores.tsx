import React, {useState} from "react"
import Filters from '@components/Filters/Filters';
import { Heading } from '@components/Typography/Heading';
import Header from '@components/Territory/Header'
import ScoreTerritory from "@components/Territory/ScoreTerritory";
import Pie from "@components/Graph/Pie";
import { Icon } from "@components/Typography/Icon";
import {Text} from "@components/Typography/Text";

const SustainabiltiesScores = ({environnementScore, economyScore, socialScore} : {
    environnementScore: number,
    economyScore: number,
    socialScore: number
}) => {
   
    return (
        <div className="print:bg-white bg-neutral-100 py-12 px-10 w-full">
           <Heading variant="display-4">Score détaillé par enjeu durable </Heading>
           <div className="mt-10 w-full flex flex-wrap justify-center gap-10 lg:gap-0 lg:justify-between">
                <Pie
                    color='#57954B'
                    percentage={environnementScore}
                    type="Environnement"
                    icon="fa-leaf"
                ></Pie>
            
                <Pie
                    color='#60A5AB'
                    percentage={economyScore}
                    type="Economie"
                    icon="fa-coins"
                ></Pie>
                <Pie
                    color='#7A9B91'
                    percentage={socialScore}
                    type="Social"
                    icon="fa-people-group"
                ></Pie>
           </div>
        </div>
    )
}

export default SustainabiltiesScores