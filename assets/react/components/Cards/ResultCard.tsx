import React, { useState} from "react";
import { Heading } from "@components/Typography/Heading";
import ProgressBar from "@components/ProgressBar/ProgressBar";
import { Text } from "@components/Typography/Text";
import { Button } from "@components/Action/Button";
import LateralPanel from "@components/Modal/LateralPanel"
import {Choice, ScoreLink} from "@screens/Resultats";

const ResultCard = ({title, links, percentage, chosenChoices, notChosenChoices, ...props} : {
    title: string,
    links: ScoreLink[],
    percentage: number,
    src?: string,
    chosenChoices: Choice[],
    notChosenChoices: Choice[],
}) => {

    const handleDropdown = (event : any) => {
        event.stopPropagation()
        setOpen(true)
    }
    const closeDropdown = () => {
        setOpen(false)
    }

    const srcImg = props.src ? props.src : 'https://images.unsplash.com/photo-1542202229-7d93c33f5d07?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
    const [open, setOpen] = useState(false)
    return (
        <div className="col-span-1">
            <div className="lg:group h-full">
                <div className="bg-white h-full flex items-stretch cursor-pointer trans-default group-hover:-translate-y-2" onClick={ event => handleDropdown(event)}>
                    <div className="h-full flex flex-col w-full">
                        <div className="h-[152px] flex-shrink-0">
                            <img
                            className="w-full h-full object-cover"
                            src={srcImg} alt=""/>
                        </div>
                        <div className="p-6 flex flex-col h-full flex-shrink-1">
                            <Heading variant={'display-5'} className="group-hover:text-primary-600 trans-default" raw={true}>{title}</Heading>
                            <div className="flex-col mt-auto pt-2 lg:pt-6">
                                <div className="flex items-center gap-10">
                                    <ProgressBar percentage={percentage}></ProgressBar>
                                    <Text className="flex-shrink-0" size={'lg'}>{percentage} %</Text>
                                </div>
                                <Button variant={'textOnly'} className="!p-0 mt-4 lg:mt-8 !w-fit" icon="fa-chevron-right">Voir en détail</Button>
                            </div>

                        </div>

                    </div>

                </div>
            </div>


            <LateralPanel
                title={title}
                links={links}
                closeDropdown={closeDropdown}
                progressBar={<ProgressBar percentage={percentage}></ProgressBar>}
                percentage={percentage}
                chosenChoices={chosenChoices}
                notChosenChoices={notChosenChoices}
                showDialog={open}
            />

        </div>

    )
}

export default ResultCard
