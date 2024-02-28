import React, { ReactComponentElement, useState } from "react";
import { LateralPanelAnim } from '@components/Animation/LateralPanel';
import { motion, AnimatePresence } from "framer-motion"
import { Icon } from '@components/Typography/Icon';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Accordion } from '@components/Accordion/Accordion';
import {Choice, ScoreLink} from "@screens/Resultats";
import ThematiqueLinks from "@components/Modal/ThematiqueLinks";

const LateralPanel = ({showDialog, closeDropdown, title, links, progressBar, chosenChoices, notChosenChoices, ...props}: {
    closeDropdown: Function,
    title: string,
    links: ScoreLink[],
    progressBar: ReactComponentElement<any, any>,
    percentage: number,
    chosenChoices: Choice[],
    notChosenChoices: Choice[],
    showDialog: boolean
}) => {

    const [open, setOpen] = useState<number>(-1);
    const handleClick = (e: any, index: number) => {
        e.preventDefault();
        setOpen(index === open ? -1 : index);
    };

    return (
        <AnimatePresence>
            {showDialog && (
            <>
                <LateralPanelAnim>
                    <div className="h-auto w-full">
                    <div onClick={() => closeDropdown()} className="cursor-pointer absolute top-6 right-6">
                        <Icon icon="fa-xmark" size="lg"></Icon>
                    </div>
                    <div className="bg-white h-full w-full p-10 min-h-screen">
                        <Heading variant={'display-4'} className="mr-10" raw={true}>{title}</Heading>
                        <div className="flex items-center gap-10 mt-6">
                            {progressBar}
                            <Text className="flex-shrink-0" size={'lg'}>{props.percentage} %</Text>
                        </div>
                        <Accordion
                            key={1}
                            question={"Ce que je fais aujourd’hui"}
                            choices={chosenChoices}
                            handleClick={(event) => handleClick(event, 1)}
                            isOpen={open === 1}
                        ></Accordion>
                        {notChosenChoices && notChosenChoices.length > 0 && <Accordion
                            key={2}
                            question={"Ce que je peux faire demain"}
                            choices={notChosenChoices}
                            handleClick={(event) => handleClick(event, 2)}
                            isOpen={open === 2}
                        ></Accordion>}

                        <ThematiqueLinks links={links}></ThematiqueLinks>
                    </div>

                    </div>


                </LateralPanelAnim>

                <motion.div
                key={`backdrop`}
                className="fixed w-screen cursor-pointer h-screen top-0 left-0 bg-black bg-opacity-50 z-[50]"
                initial={{  opacity: 0 }}
                onClick={() =>closeDropdown()}
                animate={{  opacity: 100 }}
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

export default LateralPanel
