import React, {useState} from "react";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import Tab from "@components/Content/Tab";
import { Lists } from "@react/types/Dashboard";


const activeClass= "w-full h-1 pointer-events-none bg-primary-600 absolute left-0 bottom-0 trans-default"
const mobileTabClass= 'w-1/3 relative flex-shrink-0 text-center min-w-[144px] items-center flex justify-center py-4'

const Tabs = ({lists} : {
    lists: Lists,
}) => {
    const [indexTab, setIndexTab] = useState(lists.departments !== undefined ? 0 : 1)
    
    const handleTab = (e : any) => {
        setIndexTab(parseInt(e.target.dataset.index, 10))
    }
    
    return (
        <div className="py-12">
            <div className="px-4 lg:px-10">
                <Heading variant={'display-4'} color="neutral-800">Score détaillé des répondants</Heading>
                <Text color={'neutral-700'} className="mt-4" size="sm">Retrouvez ci-dessous les détails concernant votre territoire et vos prestataires.</Text>
            </div>
            <div className="w-full lg:mt-12">
                <div className="lg:px-10 lg:grid lg:grid-cols-3 relative w-full">
                    <div className="max-lg:hidden absolute w-full h-[2px] bg-neutral-300 left-0 top-[70px] md:top-[54px]"></div>
                     {/* Version mobile TAB */}
                    
                    <div  className="lg:hidden col-span-full flex border-b border-neutral-300 items-stretch overflow-x-auto">
                        <div onClick={(e) => {handleTab(e) }} data-index={0} className={`${mobileTabClass}`}>
                            <Text weight={500} className="uppercase pointer-events-none" color="neutral-700" onClick={e => e.stopPropagation()}>{lists.departments?.length} départements</Text>
                            <div className={`${activeClass} ${indexTab === 0 ? 'opacity-100' : 'opacity-0'}`}></div>
                        </div>
                        <div onClick={(e) => {handleTab(e) }} data-index={1} className={`${mobileTabClass}`}>
                            <Text weight={500} className="uppercase pointer-events-none" color="neutral-700" onClick={e => e.stopPropagation()}>{lists.ots?.length} offices de tourisme</Text>
                            <div className={`${activeClass} ${indexTab === 1 ? 'opacity-100' : 'opacity-0'}`}></div>
                        </div>
                        <div onClick={(e) => {handleTab(e) }} data-index={2} className={`${mobileTabClass}`}>
                            <Text weight={500} className="uppercase pointer-events-none" color="neutral-700" onClick={e => e.stopPropagation()}>{lists.repondants?.length} répondants</Text>
                            <div className={`${activeClass} ${indexTab === 2 ? 'opacity-100' : 'opacity-0'}`}></div>
                        </div>
                    </div>


                    {lists.departments && lists.departments.length > 0 && <Tab type="départements" index={0} handleTab={handleTab} indexTab={indexTab} datas={lists.departments}></Tab>}
                    {lists.ots && lists.ots.length > 0 && <Tab type="offices de tourisme" index={1} handleTab={handleTab} indexTab={indexTab} datas={lists.ots}></Tab>}
                    {lists.repondants && lists.repondants.length > 0 && <Tab type="répondants" index={2} handleTab={handleTab} indexTab={indexTab} datas={lists.repondants}></Tab>}               
                </div>
            </div>
            
        </div>
    )
}

export default Tabs