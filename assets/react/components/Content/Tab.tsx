import React, { useEffect, useState} from "react";
import {Text} from "@components/Typography/Text";


const tabClass= "col-span-1 text-center cursor-pointer relative"
const activeClass= "w-full h-1 bg-primary-600 absolute left-0 bottom-0 trans-default"
const Tab = ({type, index, handleTab, indexTab, array}: {
    type: string,
    index: string,
    handleTab: Function,
    indexTab: string,
    array: any
}) => {

    const [openTab, setOpenTab] = useState(false)

    return(
        <>
            <div className={`${tabClass}`} data-index={index} onClick={(e) => {handleTab(e) }}>
                <Text weight={500} className="uppercase m-4 pointer-events-none" color="neutral-700" onClick={e => e.stopPropagation()}>{type}</Text>
                <div className={`${activeClass}  ${ index === indexTab ? 'opacity-100' : 'opacity-0' }`}></div>
            </div>
            
            {/* {openTab && <div>Hello {index}</div>} */}
            {index === indexTab && 
                <div className="col-span-full order-last">
                    <div>
                   
                    </div>
                    {array.map((el : any, key : any) => (
                        <div key={key} className="flex gap-10">
                            <div className="w-[500px]">{el.Nom}</div>
                            <div className="w-20">{el.rep}</div>
                            <div>{el.percentage}</div>
                        </div>
                    ))}
                </div>
            }
        </>
        
    )
}

export default Tab