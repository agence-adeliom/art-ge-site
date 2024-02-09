import React, { useEffect, useState} from "react";
import {Text} from "@components/Typography/Text";
import { RepondantItem, RepondantList, TerritoireItem, TerritoireList } from "@react/types/Dashboard";
import LinkTab from "@components/Action/Link/LinkTab";
import { motion, AnimatePresence } from "framer-motion"


const tabClass= "hidden lg:block col-span-1 text-center cursor-pointer relative text-sm md:text-base py-6 md:py-4"
const activeClass= "w-full h-1 pointer-events-none bg-primary-600 absolute left-0 bottom-0 trans-default"
const Tab = ({type, index, handleTab, indexTab, datas}: {
    type: string,
    index: number,
    handleTab: Function,
    indexTab: number,
    datas: RepondantList | TerritoireList
}) => {

    return(
        <>  {/* Version desktop */}
            <div className={`${tabClass}`} data-index={index} onClick={(e) => {handleTab(e) }}>
                <Text weight={500} className="uppercase pointer-events-none" color="neutral-700" onClick={e => e.stopPropagation()}>{`${datas.length} ${type}`}</Text>
                <div className={`${activeClass}  ${ index === indexTab ? 'opacity-100' : 'opacity-0' }`}></div>
            </div>
            <AnimatePresence mode="popLayout">
            {index === indexTab && 
                <motion.div  
                className="col-span-full order-last lg:pt-5 lg:-mx-10"
                initial={{  opacity: 0 }}
                animate={{  opacity: 1 }}
                exit={{ opacity: 0 }}>
                    <div >
                        {datas.map((data : RepondantItem | TerritoireItem, key : number) => {
                            if ('uuid' in data) { // liste des répondants
                                const className = "flex flex-col gap-3 lg:grid grid-cols-11 lg:gap-10 p-3 lg:px-10"
                                const hover = "lg:hover:bg-white trans-default"
                                return <React.Fragment key={key}>
                                            {key === 0 && <div className={`${className} max-lg:hidden text-sm`}><div className="font-bold col-span-3">Typologie</div><div className="font-bold col-span-3">Nom du répondant</div><div className="font-bold col-span-2">Commune</div><div className="font-bold col-span-1">Score</div><div className="font-bold col-span-2">Action</div></div>}
                                            <a href={`/resultat/${data.uuid}`}  className={`${className} ${hover} group border-b border-gray-200 bg-gray-50 w-full items-center`} target="_blank">
                                                <Text size="sm" className="text-sm col-span-3"><span className="font-bold lg:hidden">Typologie : </span>{data.typologie}</Text>
                                                <Text size="sm" className="text-sm col-span-3"><span className="font-bold lg:hidden">Nom du répondant : </span>{data.company}</Text>
                                                <Text size="sm" className="text-sm col-span-2"><span className="font-bold lg:hidden">Commune : </span>{data.city}</Text>
                                                <Text size="sm" className="text-sm col-span-1"><span className="font-bold lg:hidden">Score : </span> <span className="font-semibold">{data.total > 0 ? Math.round(data.points / data.total * 100) : 0}</span>/100</Text>
                                                <LinkTab
                                                    label="Voir le détail"
                                                    />
                                            </a>
                                    </React.Fragment>;
                            } else { // liste des départments ou ots
                                const className = "flex flex-col gap-3 md:grid lg:grid-cols-6 lg:gap-10 p-3 lg:px-10"
                                return <React.Fragment key={key}>
                                        {key === 0 && <div className={`${className} max-lg:hidden text-sm`}><div className="font-bold lg:col-span-3">Nom</div><div className="font-bold md:col-span-2">Nombre de répondants</div><div className="font-bold md:col-span-1">Score</div></div>}
                                        <div className={className + ' border-b border-gray-200 bg-gray-50 w-full'}>
                                            <Text size="sm" className="lg:col-span-3"><span className="font-bold lg:hidden">Nom : </span>{data.name}</Text>
                                            <Text size="sm" className="lg:col-span-2"><span className="font-bold lg:hidden">Nombre de répondants : </span>{data.numberOfReponses}</Text>
                                            <Text size="sm" className="lg:col-span-1"><span className="font-bold lg:hidden">Score :</span> <span className="font-semibold">{data.score}</span>/100</Text>
                                        </div>
                                    </React.Fragment>;
                            }
                        })}
                    </div>
                </motion.div>
            }
            </AnimatePresence>
        </>
        
    )
}

export default Tab