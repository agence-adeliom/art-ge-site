import React from "react";
import {Text} from "@components/Typography/Text";
import { Icon } from "@components/Typography/Icon";

const barContainer = "w-full h-[267px] relative bg-neutral-100"
const progressBar = "w-full absolute bottom-0 left-0"
const Bar = ({percentage, type} : {
    percentage: number,
    type: string
}) => {
    let color = '#B56576';
    let icon = "fa-duotone fa-map-location-dot";
    let name = 'Lieux de visite';

    if (type === 'hotel'){
        color = '#264653';
        icon = "fa-duotone fa-hotel";
        name = 'Hôtels';
    } else if (type === 'location'){
        color = '#1557b7';
        icon = "fa-duotone fa-apartment";
        name = 'Locations';
    } else if (type === 'insolite'){
        color = '#2A9D8F';
        icon = "fa-duotone fa-campground";
        name = 'Insolites';
    } else if (type === 'camping'){
        color = '#DEA823';
        icon = "fa-duotone fa-tent";
        name = 'Campings';
    } else if (type === 'restaurant'){
        color = '#C5671D';
        icon = "fa-duotone fa-utensils";
        name = 'Restaurants';
    } else if (type === 'chambre'){
        color = '#E55E3C';
        icon = "fa-duotone fa-bed-front";
        name = 'Chambres d’hôtes';   
    } else if (type === 'activite'){
        color = '#664E76';
        icon = "fa-duotone fa-tree";
        name = 'Loisirs';
    }

    return (
        <>
            <div className={`${isNaN(percentage) === false ? "text-center" : ""} w-[85px] flex-shrink-0 col-span-1 lg:w-full`} key={type}>
                {<Text className="font-title mb-2" color="neutral-600" size="2xl" dangerouslySetInnerHTML={{__html: isNaN(percentage) === false ? `${percentage}/100` : '&nbsp;'}}></Text>}
                <div className={`${barContainer}`}>
                    <div className={`${progressBar}`} style={{background: color, height: `${percentage}%`}}></div>
                </div>
                <div className="w-12 h-12 relative left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full flex items-center justify-center bg-white" style={{border: `4px solid ${color}`, color: color}}>
                    <Icon variant={'duotone'} size={'xl'} icon={icon}></Icon>
                </div>

                <Text size="sm" className="text-center">{name}</Text>
            </div>
        </>
    )
}

export default Bar