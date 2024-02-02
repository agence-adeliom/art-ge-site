import React, { useId, useState} from "react";
import { Text } from "@components/Typography/Text";
import ProgressBarTerritorySimple
    from "@components/ProgressBar/ProgressBarTerritorySimple";
import LateralPanelDashboard from "@components/Modal/LateralPanelDashboard";
import {Icon} from "@components/Typography/Icon";
import Link from "@components/Action/Link/Link";
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
            <div className="items-center flex max-lg:flex-wrap gap-4 lg:gap-8 py-3">
                <Text size="sm" weight={500} className="flex-shrink-0 w-full lg:w-[240px]">{title}</Text>
                
                <div className="w-full items-center flex gap-5">
                    <ProgressBarTerritorySimple percentage={percentage} color={color}></ProgressBarTerritorySimple>
                    <Text className="font-title block w-[74px] flex-shrink-0" size="2xl" color="neutral-600"><span className="text-black">{percentage}</span>/100</Text>
                </div>
                
                <Link
                    label="Voir le détail"
                    onClickFunction={handleDropdown}/>
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
