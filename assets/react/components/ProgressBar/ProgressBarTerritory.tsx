import React from "react";
import Leaf from "@icones/leafs-color.svg";
import {Text} from "@components/Typography/Text";

const progressContainer = "bg-neutral-200 h-4 w-full relative"
const progressBar = "bg-primary-600 h-full w-full absolute top-0 left-0 trans-default"
const number = "absolute text-black top-full mt-1"
const seuil = "absolute left-[33%] -translate-x-1/2 h-[88px] flex flex-col items-center gap-4"
const seuilIndicator = "bg-secondary-600 w-1 rounded-full"
const ProgressBarTerritory = ({percentage}: {
    percentage?: number
}) => {
    return (
        <>
        <div className="h-[88px]">
            <div className={progressContainer}>
                <div className={progressBar} style={{ width: `${percentage}%`}}></div>
                <div className={`left-0 ${number}`}>0</div>
                <div className={`right-0 ${number}`}>100</div>
                <div className={seuil}>
                    <div className={`${seuilIndicator} h-8`}></div>
                    <img src={Leaf} alt="seuil de durabilité"/>
                </div>
            </div>
        </div>
        
        <div className="mt-6 relative">
            <div className={`${seuilIndicator} h-full absolute top-0 left-0`}></div>
            <Text color="neutral-700" className="ml-5">Fixé à 33%, le seuil de durabilité est le stade à partir duquel l’engagement des prestataires et/ou du territoire est considéré comme significatif et valorisable.</Text>
        </div>
    </>
    )
}

export default ProgressBarTerritory