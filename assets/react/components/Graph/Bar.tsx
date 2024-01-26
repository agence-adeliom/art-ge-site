import React from "react";
import {Text} from "@components/Typography/Text";
import { Icon } from "@components/Typography/Icon";


const barContainer = "w-full h-[267px] relative bg-neutral-100"
const progressBar = "w-full absolute bottom-0 left-0"
const Bar = ({color, percentage, icon, type} : {
    color: string,
    percentage: number,
    icon: string,
    type: string
}) => {
    return (
        <div className="w-[calc((1/7)*100%)] text-center">
            <Text className="font-title mb-2" color="neutral-600" size="2xl">{percentage}/100</Text>
            <div className={`${barContainer}`}>
                <div className={`${progressBar}`} style={{background: color, height: `${percentage}%`}}></div>
            </div>
            <div className="w-12 h-12 relative left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full flex items-center justify-center bg-white" style={{border: `4px solid ${color}`, color: color}}>
                <Icon variant={'duotone'} size={'xl'} icon={icon}></Icon>
            </div>
            <Text size="sm">{type}</Text>

        </div>
        
    )
}

export default Bar