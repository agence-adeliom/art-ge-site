import React, { ReactComponentElement, useState } from "react";
import { LateralPanelAnim } from '@components/Animation/LateralPanel';
import { motion, AnimatePresence } from "framer-motion"
import { Icon } from '@components/Typography/Icon';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import {ThematiqueDetails} from "@components/Territory/Analysis";

const LateralPanelDashboard = ({closeDropdown, title, percentage, thematiqueDetails}: {
    closeDropdown: Function,
    title: string,
    percentage?: number,
    thematiqueDetails: ThematiqueDetails,
}) => {

    const [open, setOpen] = useState<number>(-1);
    const handleClick = (e: any, index: number) => {
        e.preventDefault();
        setOpen(index === open ? -1 : index);
    };

    return (
        <>
            <LateralPanelAnim isVisible>
                <div className="h-auto w-full">
                <div onClick={() => closeDropdown()} className="cursor-pointer absolute top-6 right-6">
                    <Icon icon="fa-xmark" size="lg"></Icon>
                </div>
                <div className="bg-white h-full w-full p-10 min-h-screen">
                    <Heading variant={'display-4'} className="mr-10" raw={true}>{title}</Heading>

                    <div className="bg-neutral-100 p-4 mt-4">
                        { thematiqueDetails.map((item) => (
                            <div key={item.slug}>
                                <p>{item.slug}</p>
                                <p>{item.name}</p>
                                <p>{item.percentage}%</p>
                            </div>
                        )) }
                    </div>
                </div>

                </div>


            </LateralPanelAnim>

            <motion.div
            key={`backdrop`}
            className="fixed w-screen h-screen top-0 left-0 bg-black bg-opacity-50 z-[50]"
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


    )
}

export default LateralPanelDashboard
