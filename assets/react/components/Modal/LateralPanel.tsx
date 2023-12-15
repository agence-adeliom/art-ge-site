import React, { ReactComponentElement, useState } from "react";
import { LateralPanelAnim } from '@components/Animation/LateralPanel';
import { motion, AnimatePresence } from "framer-motion"
import { Icon } from '@components/Typography/Icon';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Accordion } from '@components/Accordion/Accordion';


const LateralPanel = ({closeDropdown, title, progressBar, ...props}: {
    closeDropdown: Function, 
    title: string,
    progressBar: ReactComponentElement<any, any>,
    percentage: number
}) => {

    const [open, setOpen] = useState<number>(-1);
    const handleClick = (e: any, index: number) => {
        e.preventDefault();
        setOpen(index === open ? -1 : index);
    };
    const data = [
        {
            id: 1,
            question: "Ce que je fais aujourd’hui",
            attributes: {
                name: "Censure"
            }
            
        },
        {
            id: 2,
            question: "Ce que je peux faire demain",
            attributes: {
                name: "Censure"
            }
        },
    ]
    

    const answer = [
        ["Ne jamais utiliser d'insecticides"],
        ["Limiter drastiquement l'éclairage nocturne (les lumières extérieures sont éteintes au plus tard 2h après le coucher du soleil, sans passage)"],
        ["Disposer d'une mare ou d'un plan d'eau végétalisé sur au moins 1% du terrain"]
    ]
    return (
        <>
            <LateralPanelAnim isVisible>
                <div className="h-auto w-full">
                <div onClick={() => closeDropdown()} className="cursor-pointer absolute top-6 right-6">
                    <Icon icon="fa-xmark" size="lg"></Icon>
                </div>
                <div className="bg-white h-full w-full p-10 min-h-screen">
                    <Heading variant={'display-4'} className="mr-10">{title}</Heading> 
                    <div className="flex items-center gap-10 mt-6">
                        {progressBar}
                        <Text className="flex-shrink-0" size={'lg'}>{props.percentage} %</Text>
                    </div>
                    {data.map((item, index) => {
                        return (
                            
                            <Accordion 
                                key={index}
                                question={item.question}
                                answer={answer}
                                handleClick={(event) => handleClick(event, index)}
                                isOpen={open === index}
                            ></Accordion>
                        
                        );
                    })}

                    <div className="bg-neutral-100 p-4 mt-4">
                        <Text weight={600}>Pour aller plus loin...</Text>
                        <div className="flex flex-col gap-2 mt-2">
                            <div className="flex gap-2 items-center">
                                <Icon icon={'fa-file'} color="primary-600"></Icon>
                                <Text weight={600} color="primary-600">Pour aller plus loin...</Text>
                            </div>
                            <div className="flex gap-2 items-center">
                                <Icon icon={'fa-arrow-up-right-from-square'} color="primary-600"></Icon>
                                <Text weight={600} color="primary-600">Pour aller plus loin...</Text>
                            </div>
                            <div className="flex gap-2 items-center">
                                <Icon icon={'fa-circle-play'} color="primary-600"></Icon>
                                <Text weight={600} color="primary-600">Pour aller plus loin...</Text>
                            </div>
                        </div>
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

export default LateralPanel