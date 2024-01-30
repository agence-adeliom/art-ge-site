import React, {useState} from "react";
import { Heading } from "@components/Typography/Heading";
import { Text } from "@components/Typography/Text";
import { Icon } from "@components/Typography/Icon";
import ProgressBarTerritorySimple from "@components/ProgressBar/ProgressBarTerritorySimple";
import DurabilityCursor from "@components/Graph/DurabilityCursor";
import LateralPanel from "@components/Modal/LateralPanel";
import ProgressBar from "@components/ProgressBar/ProgressBar";
import LateralPanelDashboard from "@components/Modal/LateralPanelDashboard";
import ThematiqueRow from "@components/Territory/ThematiqueRow";

interface ThematiqueDetail {
    slug: string,
    name: string,
    percentage: number,
}

export type ThematiqueDetails = ThematiqueDetail[]

const Analysis = ({type, color, percentage, desc, barColor, icon, slug} : {
    type: string,
    color: any,
    percentage: number,
    desc: string,
    barColor: any,
    icon: string,
    slug: string,
}) => {
    const [array, setArray] = useState([
        {
            name: 'Biodiversité',
            slug: 'biodiversite-et-conservation-de-la-nature-sur-site',
            percentage: 42
        },
        {
            name: 'Eau',
            slug: 'gestion-de-l-eau-et-de-l-erosion',
            percentage: 36
        },
        {
            name: 'Entretien',
            slug: 'entretien-et-proprete',
            percentage: 27
        },
        {
            name: 'Énergie',
            slug: 'gestion-de-l-energie',
            percentage: 33
        },
    ])

    const fetchData = (t: string) => async () : Promise<void> => {
        const localStorageKey = `thematique-${slug}-${t}`; // TODO handle when there are query params, add them the the id of the key
        const localStorageValue = window.localStorage.getItem(localStorageKey);
        if (localStorageValue) {
            try {
                const thematiqueDetails = JSON.parse(localStorageValue);
                setThematiqueDetails(thematiqueDetails);
                return;
            } catch (e) {
                console.error(e);
            }
        }

        const res = await fetch(`/api/dashboard/${slug}/thematique/${t}`);
        const thematique = await res.json() as {
            success: true,
            data: ThematiqueDetails
        } | {
            success: false,
            data: string
        };

        if (Array.isArray(thematique.data)) {
            window.localStorage.setItem(localStorageKey, JSON.stringify(thematique.data));
            setThematiqueDetails(thematique.data);
        } else {
            setThematiqueDetails([]);
        }
    }

    const [thematiqueDetails, setThematiqueDetails] = useState<ThematiqueDetails>([]);

    return (
        <div className="px-10 print:py-4 py-12 print:bg-white bg-gray-50 relative">
            <div className="absolute right-10 top-0">
            <Icon icon={icon} size={null} color={color} className="text-[144px] opacity-20"></Icon>
            </div>
            <div className="flex gap-4 items-center">
                <Heading variant={'display-5'} color={color}>{type}</Heading>
                <span>-</span>
                <Text size="2xl" color="neutral-700" className="font-title mt-2"><span className={`text-4xl text-${color}`}>{percentage}</span>/100</Text>
            </div>
            <Text dangerouslySetInnerHTML={{__html: desc}}></Text>
            <div className="mt-8 relative">
                {array.map((item, index) => (
                    <ThematiqueRow key={index} title={item.name} percentage={item.percentage} color={barColor} fetchData={fetchData(item.slug)} thematiqueDetails={thematiqueDetails}></ThematiqueRow>
                ))}
                <div className="print:hidden h-full absolute w-1 border-r border-dashed border-neutral-500 top-0 left-[568px]"></div>
            </div>
            <DurabilityCursor />
        </div>
    )
}

export default Analysis
