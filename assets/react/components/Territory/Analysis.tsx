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
import { useParams } from "react-router-dom";
import { DateRange, SelectedTerritoires, Thematiques } from "@react/types/Dashboard";
import { getSearchParamsFromTerritories } from "@screens/Territory";
import {ScoreLink} from "@screens/Resultats";

interface ThematiqueDetail {
    slug: string,
    name: string,
    percentage: number,
}

export type ThematiqueDetails = ThematiqueDetail[]

const Analysis = ({type, color, percentage, desc, barColor, icon, thematiques, selectedTerritoires, dateRange} : {
    type: string,
    color: any,
    percentage: number,
    desc: string,
    barColor: any,
    icon: string,
    thematiques: Thematiques,
    selectedTerritoires: SelectedTerritoires,
    dateRange: DateRange,
}) => {
    const { territoire = 'grand-est' } = useParams();

    const [thematiqueDetails, setThematiqueDetails] = useState<ThematiqueDetails>([]);
    const [thematiqueLinks, setThematiqueLinks] = useState<ScoreLink[]>([]);

    const fetchData = (t: string) => async () : Promise<void> => {
        const search = getSearchParamsFromTerritories(selectedTerritoires, dateRange);

        const localStorageKey = `thematique-${territoire}-${t}-${search}`;
        const localStorageKeyLinks = localStorageKey + 'links';

        const localStorageValue = window.localStorage.getItem(localStorageKey);
        const localStorageValueLinks = window.localStorage.getItem(localStorageKeyLinks);

        if (localStorageValue) {
            try {
                const thematiqueDetails = JSON.parse(localStorageValue);
                setThematiqueDetails(thematiqueDetails);
                return;
            } catch (e) {
                console.error(e);
            }
        }

        if (localStorageValueLinks) {
            try {
                const links = JSON.parse(localStorageValueLinks);
                setThematiqueLinks(links);
                return;
            } catch (e) {
                console.error(e);
            }
        }

        const res = await fetch(`/api/dashboard/${territoire}/thematique/${t}?${search}`);
        const thematique = await res.json() as {
            success: true,
            links: ScoreLink[],
            data: ThematiqueDetails
        } | {
            success: false,
            links: ScoreLink[],
            data: string
        };

        if (Array.isArray(thematique.data)) {
            window.localStorage.setItem(localStorageKey, JSON.stringify(thematique.data));
            window.localStorage.setItem(localStorageKeyLinks, JSON.stringify(thematique.links));
            setThematiqueDetails(thematique.data);
            setThematiqueLinks(thematique.links);
        } else {
            setThematiqueDetails([]);
            setThematiqueLinks([]);
        }
    }

    return (
        <div className="px-4 lg:px-10 print:py-4 py-12 print:bg-white bg-gray-50 relative"  id={`${type}-analysis`}>
            <div className="absolute right-10 top-0">
            <Icon icon={icon} size={null} color={color} className="text-4xl lg:text-[144px] opacity-20"></Icon>
            </div>
            <div className="flex gap-4 items-center mb-2">
                <Heading variant={'display-5'} color={color}>{type}</Heading>
                <span>-</span>
                <Text size="2xl" color="neutral-700" className="font-title mt-2"><span className={`text-4xl text-${color}`}>{percentage}</span>/100</Text>
            </div>
            <Text dangerouslySetInnerHTML={{__html: desc}} color="neutral-700" size="sm"></Text>
            <div className="mt-8 relative">
                {thematiques.map((thematique, index) => (
                    <ThematiqueRow key={index} title={thematique.name} percentage={parseInt(thematique.score, 10)} color={barColor} fetchData={fetchData(thematique.slug)} thematiqueDetails={thematiqueDetails} thematiqueLinks={thematiqueLinks}></ThematiqueRow>
                ))}
            </div>
            <DurabilityCursor />
        </div>
    )
}

export default Analysis
