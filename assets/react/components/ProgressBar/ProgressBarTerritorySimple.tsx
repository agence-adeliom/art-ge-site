import React from "react";
import Leaf from "@icones/leafs-color.svg";
import {Text} from "@components/Typography/Text";

const progressContainer = "bg-neutral-200 h-4 w-full"
const progressBar = "h-full w-full relative animation-progress"
const ProgressBarTerritorySimple = ({percentage, color, separator = true}: {
    percentage?: number,
    color: number,
    separator?: boolean
}) => {
    return (
        <>
            <div className="w-full relative">
                {separator && <div className="print:hidden h-[calc(100%+2.5rem)] absolute w-[1px] border-r border-dashed border-neutral-500 top-[-1.25rem] left-[33%] z-10"></div>}
                <div className={progressContainer}>
                    <div className={`${progressBar}`} style={{ width: `${percentage}%`, background: color}}></div>
                </div>
            </div>
        </>
    )
}

export default ProgressBarTerritorySimple