import React from "react";

const progressContainer = "bg-primary-200 rounded-full h-2 w-full relative"
const progressBar = "bg-primary-600 rounded-full h-full w-full absolute top-0 left-0"
const ProgressBar = ({percentage}: {
    percentage?: number
}) => {
    return (
        <div className={progressContainer}>
            <div className={progressBar} style={{ width: `${percentage}%`}}></div>
        </div>
    )
}

export default ProgressBar