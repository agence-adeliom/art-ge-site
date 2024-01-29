import React, {useState} from "react";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import Tab from "@components/Content/Tab";

const Tabs = () => {
    const [indexTab, setIndexTab] = useState('0')
    const handleTab = (e : any) => {
        console.log(e)
        setIndexTab((e.target.dataset.index).toString())
    }

    const array1 = [
        {'Nom' : 'Ardennes', 'rep' :134, 'percentage' : 33 },
        {'Nom' : 'Aube', 'rep' :113, 'percentage' : 58 },
        {'Nom' : 'Marne', 'rep' :91, 'percentage' : 68 },
        {'Nom' : 'Haute-Marne', 'rep' :32, 'percentage' : 25 },
        {'Nom' : 'Meurthe et Moselle', 'rep' :78, 'percentage' : 42 },
    ]

    const array2 = [
        {'Nom' : 'CC de Vitry, Champagne et Der', 'rep' :64, 'percentage' : 84 },
        {'Nom' : 'CC des Paysages de la Champagne', 'rep' :52, 'percentage' : 77 },
        {'Nom' : 'CA de Châlons-en-Champagne', 'rep' :21, 'percentage' : 68 },
    ]

    const array3 = [
        {'Typologie' : 'Hotel', 'Name' : 'Hotel du roi soleil', 'percentage' : 33 },
        {'Typologie' : 'Hébergements insolites', 'Name' : 'La Fuste du Trappeur', 'percentage' : 42 },
        {'Typologie' : 'Campings ou locatifs de plein air', 'Name' : 'Camping de la Liez', 'percentage' : 24 },
       
    ]
    return (
        <div className="px-10 py-12">
            <Heading variant={'display-4'} color="neutral-800">Score détaillé des répondants</Heading>
            <Text color={'neutral-700'} size="sm">Retrouvez ci-dessous les détails concernant votre territoire et vos prestataires.</Text>
            <div className="grid grid-cols-3">
            
                <Tab type="départements" index="0" handleTab={handleTab} indexTab={indexTab} array={array1}></Tab>
                <Tab type="offices de tourisme" index="1" handleTab={handleTab} indexTab={indexTab} array={array2}></Tab>
                <Tab type="répondants" index="2" handleTab={handleTab} indexTab={indexTab} array={array3}></Tab>
                
           
                
               
            </div>
               
               
           
        </div>
    )
}

export default Tabs