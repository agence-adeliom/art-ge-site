import React, { useEffect, useState} from "react";
import {Text} from "@components/Typography/Text";
import { RepondantItem, RepondantList, TerritoireItem, TerritoireList } from "@react/types/Dashboard";
import Link from "@components/Action/Link/Link";

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
            
            {index === indexTab && 
                <div className="col-span-full order-last lg:pt-5 lg:-mx-10">
                    {datas.map((data : RepondantItem | TerritoireItem, key : number) => {
                        if ('uuid' in data) { // liste des répondants
                            const className = "block flex-wrap md:grid grid-cols-5 gap-10 p-3 lg:px-10"
                            return <React.Fragment key={key}>
                                        {key === 0 && <div className={`${className} max-md:hidden`}><div className="font-bold">Typologie</div><div className="font-bold">Nom du répondant</div><div className="font-bold">Commune</div><div className="font-bold">Score</div><div className="font-bold">Action</div></div>}
                                        <a href={`/resultat/${data.uuid}`}  className={className + ' border-b border-gray-200 bg-gray-50 w-full'} target="_blank">
                                            <p><span className="font-bold md:hidden">Typologie : </span>{data.typologie}</p>
                                            <p><span className="font-bold md:hidden">Nom du répondant : </span>{data.company}</p>
                                            <p><span className="font-bold md:hidden">Commune : </span>{data.city}</p>
                                            <p><span className="font-bold md:hidden">Score : </span>{data.total > 0 ? Math.round(data.points / data.total * 100) : 0}/100</p>
                                            <Link
                                                label="Voir le détail"
                                                />
                                        </a>
                                </React.Fragment>;
                        } else { // liste des départments ou ots
                            // const className = "block md:grid grid-cols-[minmax(0,500px)_minmax(5rem,1fr)_minmax(5rem,1fr)] gap-10 p-3 lg:px-10"
                            const className = "flex flex-col gap-3 md:grid md:grid-cols-6 md:gap-10 p-3 lg:px-10"
                            return <React.Fragment key={key}>
                                    {key === 0 && <div className={`${className} max-md:hidden`}><div className="font-bold md:col-span-3">Nom</div><div className="font-bold md:col-span-2">Nombre de répondants</div><div className="font-bold md:col-span-1">Score</div></div>}
                                    <div className={className + ' border-b border-gray-200 bg-gray-50 w-full'}>
                                        <p className="md:col-span-3"><span className="font-bold md:hidden">Nom : </span>{data.name}</p>
                                        <p className="md:col-span-2"><span className="font-bold md:hidden">Nombre de répondants : </span>{data.numberOfReponses}</p>
                                        <p className="md:col-span-1"><span className="font-bold md:hidden">Score :</span> <span className="font-semibold">{data.score}</span>/100</p>
                                    </div>
                                </React.Fragment>;
                        }
                    })}
                </div>
            }
        </>
        
    )
}

export default Tab