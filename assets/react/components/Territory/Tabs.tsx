import React, {useState} from "react";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import Tab from "@components/Content/Tab";
import { Lists } from "@screens/Territory";

const Tabs = ({lists} : {
    lists: Lists,
}) => {
    const [indexTab, setIndexTab] = useState(lists.departments !== undefined ? 0 : 1)
    console.log('indexTab : ',indexTab, indexTab===1);
    
    const handleTab = (e : any) => {
        setIndexTab(parseInt(e.target.dataset.index, 10))
    }
    
    return (
        <div className="px-10 py-12">
            <Heading variant={'display-4'} color="neutral-800">Score détaillé des répondants</Heading>
            <Text color={'neutral-700'} size="sm">Retrouvez ci-dessous les détails concernant votre territoire et vos prestataires.</Text>
            <div className="grid grid-cols-3">
                {lists.departments && lists.departments.length > 0 && <Tab type="départements" index={0} handleTab={handleTab} indexTab={indexTab} datas={lists.departments}></Tab>}
                {lists.ots && lists.ots.length > 0 && <Tab type="offices de tourisme" index={1} handleTab={handleTab} indexTab={indexTab} datas={lists.ots}></Tab>}
                {lists.repondants && lists.repondants.length > 0 && <Tab type="répondants" index={2} handleTab={handleTab} indexTab={indexTab} datas={lists.repondants}></Tab>}               
            </div>
        </div>
    )
}

export default Tabs