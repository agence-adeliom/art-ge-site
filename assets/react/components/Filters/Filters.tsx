import React, { ReactNode, useState} from "react"
import Logo from '@images/logo/logo.png';
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';
import Filter from '@components/Filters/Filter';
import { Button } from '@components/Action/Button'; 

import 'react-dates/initialize';
import 'react-dates/lib/css/_datepicker.css';
import { SelectedTerritoires } from "@react/types/Dashboard";
import { useParams } from "react-router-dom";

const Filters = ({apiData, children, setOt, setFilterMobile, ot, etablishment, territories, departments, lastSubmission, setSelectedTerritoires, selectedTerritoires} : {
    apiData: Function,
    apiFilter: Function,
    setFilterMobile: Function,
    ot: any,
    etablishment: any,
    territories: any,
    departments: any,
    lastSubmission: string,
    setSelectedTerritoires: Function,
    selectedTerritoires: SelectedTerritoires,
    setOt: Function,
    children: ReactNode
}) => {
    const { territoire = 'grand-est' } = useParams();
    // cf DepartementEnum.php
    const predefinedDepartments = ['alsace', 'ardennes', 'aube', 'marne', 'haute-marne', 'meurthe-et-moselle', 'meuse', 'moselle', 'vosges'] as const;

    const [departmentsFilter, setDepartmentsFilter] = useState()
    const [otsFilter, setOtsFilter] = useState()
    const [territoriesFilter, setTerritoriesFilter] = useState()
    const [EstablishmentsFilter, setEstablishmentsFilter] = useState()

    const [filterId, setFilterId] = useState<number | null>(null)

    return (
        <div className="flex flex-col min-h-full h-full">
            <a href="https://www.art-grandest.fr/" target='_blank' className="hidden md:block">
                <img src={Logo} alt="Logo ART GE" className=""/>
            </a>
            <div className="relative">
                <div onClick={(e) => {e.stopPropagation(),setFilterMobile(false)}} className="absolute top-4 right-4 cursor-pointer md:hidden">
                    <Icon icon="fa-solid fa-xmark"></Icon>
                </div>
                <Text color="neutral-700" className="md:mt-12" weight={400}>
                    Filtrer par :
                </Text>
                    <div onClick={() => setFilterId(1)}>
                        <Filter id={1} filterId={filterId} setFilterId={setFilterId} setOt={setOt} filterValue={territoriesFilter} allFilter={territories} type={'Territoires'} setFilterValue={setTerritoriesFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>
                    { territoire === 'grand-est' && <div onClick={() => setFilterId(2)}>
                        <Filter id={2} filterId={filterId} setFilterId={setFilterId} setOt={setOt}  filterValue={departmentsFilter} allFilter={departments} type={'Départements'} setFilterValue={setDepartmentsFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>}
                    { (territoire === 'grand-est' || predefinedDepartments.includes(territoire)) && <div onClick={() => setFilterId(3)}>
                        <Filter id={3} filterId={filterId} setFilterId={setFilterId} setOt={setOt}  filterValue={otsFilter} allFilter={ot} type={'Offices de tourisme'} setFilterValue={setOtsFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>}
                    <div onClick={() => setFilterId(4)}>
                        <Filter id={4} filterId={filterId} setFilterId={setFilterId}  setOt={setOt} filterValue={EstablishmentsFilter} allFilter={etablishment} type={'Établissements'} setFilterValue={setEstablishmentsFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>

                <div className="border-b border-neutral-300 pb-2" onClick={() => setFilterId(null)}>
                    <Text className="mt-6 mb-3" size="sm">Période :</Text>
                    { children }
                </div>
            </div>
            
            <Button 
                variant="secondary" 
                icon="fa-solid fa-minus" 
                iconSide="left"
                className="mt-4 md:w-full justify-end"
                size={'lg'}
                onClick={() => {apiData(), setFilterMobile(false), setFilterId(null)}}
                >
                    Filtrer les résultats
            </Button>

            <Button
            variant="resetFilter"
            className="p-0 mt-4"
            onClick={() =>location.reload()}>
                <Icon icon="fa-solid fa-arrows-rotate"></Icon>
                Réinitialiser les filtres
            </Button>

            {lastSubmission && <Text weight={400} size="sm" color="neutral-500" className="mt-auto py-10 ">Dernière mise à jour : {lastSubmission}</Text>}
        </div>
    )
}


export default Filters