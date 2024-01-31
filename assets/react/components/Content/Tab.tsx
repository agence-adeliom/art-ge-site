import React, { useEffect, useState} from "react";
import {Text} from "@components/Typography/Text";
import { RepondantItem, RepondantList, TerritoireItem, TerritoireList } from "@react/types/Dashboard";


const tabClass= "col-span-1 text-center cursor-pointer relative"
const activeClass= "w-full h-1 bg-primary-600 absolute left-0 bottom-0 trans-default"
const Tab = ({type, index, handleTab, indexTab, datas}: {
    type: string,
    index: number,
    handleTab: Function,
    indexTab: number,
    datas: RepondantList | TerritoireList
}) => {
    const [openTab, setOpenTab] = useState(false)

    return(
        <>
            <div className={`${tabClass}`} data-index={index} onClick={(e) => {handleTab(e) }}>
                <Text weight={500} className="uppercase m-4 pointer-events-none" color="neutral-700" onClick={e => e.stopPropagation()}>{type}</Text>
                <div className={`${activeClass}  ${ index === indexTab ? 'opacity-100' : 'opacity-0' }`}></div>
            </div>
            
            {index === indexTab && 
                <div className="col-span-full order-last">
                    {datas.map((data : RepondantItem | TerritoireItem, key : number) => {
                        if ('uuid' in data) { // liste des répondants
                            const className = "grid grid-cols-5 gap-10 p-3"
                            return <React.Fragment key={key}>
                                        {key === 0 && <div className={className}><div className="font-bold">Typologie</div><div className="font-bold">Nom du répondant</div><div className="font-bold">Commune</div><div className="font-bold">Score</div><div className="font-bold">Action</div></div>}
                                        <a href={`/resultat/${data.uuid}`}  className={className + ' border-b border-gray-200 bg-gray-50'}>
                                            <p>{data.typologie}</p>
                                            <p>{data.company}</p>
                                            <p>{data.city}</p>
                                            <p>{data.total > 0 ? Math.round(data.points / data.total * 100) : 0}/100</p>
                                            <p>Voir le détail</p>
                                        </a>
                                </React.Fragment>;
                        } else { // liste des départments ou ots
                            const className = "grid grid-cols-[minmax(0,500px)_minmax(5rem,1fr)_minmax(5rem,1fr)] gap-10 p-3"
                            return <React.Fragment key={key}>
                                    {key === 0 && <div className={className}><div className="font-bold">Nom</div><div className="font-bold">Nombre de répondants</div><div className="font-bold">Score</div></div>}
                                    <div className={className + ' border-b border-gray-200 bg-gray-50'}>
                                        <p>{data.name}</p>
                                        <p>{data.numberOfReponses}</p>
                                        <p>{data.score}/100</p>
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