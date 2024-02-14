import React, { ReactComponentElement, useState } from "react";
import { LateralPanelAnim } from '@components/Animation/LateralPanel';
import { motion, AnimatePresence } from "framer-motion"
import { Icon } from '@components/Typography/Icon';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { ThematiqueDetails } from "@components/Territory/Analysis";
import ProgressBarTerritorySimple from "@components/ProgressBar/ProgressBarTerritorySimple";
import {ScoreLink} from "@screens/Resultats";
import ThematiqueLinks from "@components/Modal/ThematiqueLinks";

const LateralPanelDashboard = ({closeDropdown, showDialog, title, barColor, percentage, thematiqueDetails, thematiqueLinks}: {
    closeDropdown: Function,
    title: string,
    barColor: number,
    percentage?: number,
    thematiqueDetails: ThematiqueDetails,
    thematiqueLinks: ScoreLink[],
    showDialog: boolean
}) => {

    const [open, setOpen] = useState<number>(-1);
    const handleClick = (e: any, index: number) => {
        e.preventDefault();
        setOpen(index === open ? -1 : index);
    };

    return (
        <AnimatePresence>
        { showDialog && (
        <>
            <LateralPanelAnim>
                <div className="h-auto w-full">
                <div onClick={() => closeDropdown()} className="cursor-pointer absolute top-6 right-6">
                    <Icon icon="fa-xmark" size="lg"></Icon>
                </div>
                <div className="bg-white h-full w-full p-10 min-h-screen">
                    <Heading variant={'display-4'} className="mr-10 mb-10" raw={true}>{title}</Heading>
                    <Text color="neutral-700" className="mb-4">Moyenne régionnale : <strong>{percentage}/100</strong></Text>
                    <Text color="neutral-700" className="mb-4">Les actions des répondants :</Text>
                    <div>
                        { thematiqueDetails.map((item) => (
                            <div key={item.slug} className="border-b py-3">
                                <Text color="neutral-700" className="flex-shrink-0">{item.name}</Text>
                                <div className="grid grid-cols-[1fr_80px] gap-6 items-center">
                                    <ProgressBarTerritorySimple percentage={item.percentage} color={barColor} separator={false}></ProgressBarTerritorySimple>
                                    <Text className="font-title" size="2xl" color="neutral-600"><span className="text-black">{item.percentage}</span>/100</Text>
                                </div>
                            </div>
                        )) }
                    </div>
                    <ThematiqueLinks links={thematiqueLinks}></ThematiqueLinks>
                </div>

                </div>

            </LateralPanelAnim>

            <motion.div
            key={`backdrop`}
            className="fixed w-screen cursor-pointer h-screen top-0 left-0 bg-black bg-opacity-50 z-[50]"
            initial={{  opacity: 0 }}
            onClick={() =>closeDropdown()}
            animate={{  opacity: 100 }}
            exit={{  opacity: 0 }}
            transition={{
                ease: "easeIn",
                duration: 0.3
              }}
            >
            </motion.div>

            </>
            )}
        </AnimatePresence>


    )
}

export default LateralPanelDashboard
