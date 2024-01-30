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
            <div className="items-center flex gap-8 my-3">
                <Text size="sm" weight={500} className="w-[210px] flex-shrink-0">{title}</Text>
                <ProgressBarTerritorySimple percentage={percentage} color={color}></ProgressBarTerritorySimple>
                <Text className="font-title" size="2xl" color="neutral-600"><span className="text-black">{percentage}</span>/100</Text>
                <p className="ml-3 mr-7 flex-shrink-0" onClick={ event => handleDropdown(event)}>Voir le détail <Icon icon="fa-solid fa-chevron-right"></Icon></p>
            </div>

            {open && (
                <LateralPanelDashboard
                    title={title}
                    percentage={percentage}
                    thematiqueDetails={thematiqueDetails}
                    closeDropdown={closeDropdown} />
            )}

        </>

    )
}

export default ThematiqueRow
