import React, {useState} from "react";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import { Icon } from "@components/Typography/Icon";
import ProgressBarTerritorySimple from "@components/ProgressBar/ProgressBarTerritorySimple";
import DurabilityCursor from "@components/Graph/DurabilityCursor";

const Analysis = ({type, color, percentage, desc, barColor, icon} : {
    type: string,
    color: any,
    percentage: number,
    desc: string,
    barColor: any,
    icon: string
}) => {
    const [array, setArray] = useState([
        {
            name: 'Biodiversité',
            percentage: 42
        },
        {
            name: 'Eau',
            percentage: 36
        },
        {
            name: 'Entretien',
            percentage: 27
        },
        {
            name: 'Énergie',
            percentage: 33
        },
    ])
    return (
        <div className="px-10 print:py-4 py-12 print:bg-white bg-gray-50 relative">
            <div className="absolute right-10 top-0">
            <Icon icon={icon} size={null} color={color} className="text-[144px] opacity-20"></Icon>
            </div>
            <div className="flex gap-4 items-center">
                <Heading variant={'display-5'} color={color}>{type}</Heading>
                <span>-</span>
                <Text size="2xl" color="neutral-700" className="font-title mt-2"><span className={`text-4xl text-${color}`}>{percentage}</span>/100</Text>
            </div>
            <Text dangerouslySetInnerHTML={{__html: desc}}></Text>
            <div className="mt-8 relative">
                {array.map((item, index) => (
                    <div key={index} className="items-center flex gap-8 my-3">
                        <Text size="sm" weight={500} className="w-[210px] flex-shrink-0">{item.name}</Text>
                        <ProgressBarTerritorySimple percentage={item.percentage} color={barColor}></ProgressBarTerritorySimple>
                        <Text className="font-title" size="2xl" color="neutral-600"><span className="text-black">{item.percentage}</span>/100</Text>
                        <p className="ml-3 mr-7 flex-shrink-0">Voir le détail <Icon icon="fa-solid fa-chevron-right"></Icon></p>
                    </div>
                ))}
                <div className="print:hidden h-full absolute w-1 border-r border-dashed border-neutral-500 top-0 left-[568px]"></div>
            </div>
            <DurabilityCursor />
        </div>
    )
}

export default Analysis