import React, { useId, useState} from "react";
import { Text } from "@components/Typography/Text";
import ProgressBarTerritorySimple
    from "@components/ProgressBar/ProgressBarTerritorySimple";
import LateralPanelDashboard from "@components/Modal/LateralPanelDashboard";
import {Icon} from "@components/Typography/Icon";
import {ThematiqueDetails} from "@components/Territory/Analysis";

const ThematiqueRow = ({title, percentage, color, thematiqueDetails, fetchData} : {
    title: string,
    percentage?: number,
    color: number,
    thematiqueDetails: ThematiqueDetails,
    fetchData: () => void
}) => {

    const handleDropdown = async (event : any) => {
        fetchData()
        event.stopPropagation()
        setOpen(true)
    }
    const closeDropdown = () => {
        setOpen(false)
    }

    const [open, setOpen] = useState(false)
    return (
        <>
            <div className="items-center grid grid-cols-1 md:grid-cols-[minmax(0,250px)_minmax(0,_1fr)_80px_110px] gap-8 py-3">
                <Text size="sm" weight={500} className="flex-shrink-0">{title}</Text>
                <ProgressBarTerritorySimple percentage={percentage} color={color}></ProgressBarTerritorySimple>
                <Text className="font-title" size="2xl" color="neutral-600"><span className="text-black">{percentage}</span>/100</Text>
                <p className="flex-shrink-0 cursor-pointer" onClick={ event => handleDropdown(event)}>Voir le détail <Icon icon="fa-solid fa-chevron-right"></Icon></p>
            </div>

            {open && (
                <LateralPanelDashboard
                    title={title}
                    percentage={percentage}
                    thematiqueDetails={thematiqueDetails}
                    barColor={color}
                    closeDropdown={closeDropdown} />
            )}

        </>

    )
}

export default ThematiqueRow
