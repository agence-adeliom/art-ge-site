import React from "react";
import Leaf from "@icones/leafs-color.svg";
import {Text} from "@components/Typography/Text";

const progressContainer = "bg-neutral-200 h-4 w-full relative"
const progressBar = " h-full w-full absolute top-0 left-0 trans-default"
const ProgressBarTerritorySimple = ({percentage, color}: {
    percentage?: number,
    color: number
}) => {
    return (
        <>
        <div className="w-full">
            <div className={progressContainer}>
                <div className={`${progressBar}`} style={{ width: `${percentage}%`, background: color}}></div>
            </div>
        </div>
    </>
    )
}

export default ProgressBarTerritorySimple