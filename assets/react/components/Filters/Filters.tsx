import React, {useEffect, useState} from "react"
import Logo from '@images/logo/logo.png';
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';
import Filter from '@components/Filters/Filter';
import { Button } from '@components/Action/Button'; 
import moment, { Moment } from 'moment';

import 'react-dates/initialize';
import { DateRangePicker,  } from 'react-dates';
import 'react-dates/lib/css/_datepicker.css';
import { SelectedTerritoires } from "@react/types/Dashboard";


const Filters = ({apiData, filters, ot, etablishment, territories, departments, lastSubmission, setSelectedTerritoires, selectedTerritoires} : {
    apiData: Function,
    filters: any,
    ot: any,
    etablishment: any,
    territories: any,
    departments: any,
    lastSubmission: string,
    setSelectedTerritoires: Function,
    selectedTerritoires: SelectedTerritoires,
}) => {

    const [departmentsFilter, setDepartmentsFilter] = useState()
    const [otsFilter, setOtsFilter] = useState()
    const [territoriesFilter, setTerritoriesFilter] = useState()
    const [EstablishmentsFilter, setEstablishmentsFilter] = useState()

    useEffect(() => {
        if(filters) {
            setDepartmentsFilter(departments)
            setOtsFilter(ot)
            setTerritoriesFilter(territories)
            setEstablishmentsFilter(etablishment)
        }
    }, [filters])

    // French locale
    moment.locale('fr-fr');
    const [startDate, setStartDate] = useState(null);
    const [endDate, setEndDate] = useState(null);
    const [focusedInput, setFocusedInput] = useState(null);

    const [filterStartDate, setFilterStartDate] = useState<string>();
    const [filterEndDate, setFilterEndDate] = useState<string>();
    

    const handleDateChange = (date : any) => {
        const jour = ("0" + date.getDate()).slice(-2);
        const mois = ("0" + (date.getMonth() + 1)).slice(-2);
        const annee = date.getFullYear();
        
        return `${jour}/${mois}/${annee}`;
    }

    
    useEffect(() => {
        if (startDate) {
            const dateOriginale = new Date(startDate!['_d']);
            setFilterStartDate(handleDateChange(dateOriginale));
        }
    }, [startDate])

    useEffect(() => {
        if (endDate) {
            const dateOriginale = new Date(endDate!['_d']);
            setFilterEndDate(handleDateChange(dateOriginale));
        }
    }, [endDate])


    const [filterId, setFilterId] = useState<number | null>(null)

  

    return (
        <div className="flex flex-col min-h-full h-full overflow-scroll">
            <a href="https://www.art-grandest.fr/" target='_blank' >
                <img src={Logo} alt="Logo ART GE" className=""/>
            </a>
            <div className="relative">
                <Text color="neutral-700" className="mt-12" weight={400}>
                    Filtrer par :
                </Text>
                    <div onClick={() => setFilterId(1)}>
                        <Filter id={1} filterId={filterId} setFilterId={setFilterId} filterValue={territoriesFilter} allFilter={territories} type={'Territoires'} setFilterValue={setTerritoriesFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>
                    <div onClick={() => setFilterId(2)}>
                        <Filter id={2} filterId={filterId} setFilterId={setFilterId} filterValue={departmentsFilter} allFilter={departments} type={'Départements'} setFilterValue={setDepartmentsFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>
                    <div onClick={() => setFilterId(3)}>
                        <Filter id={3} filterId={filterId} setFilterId={setFilterId} filterValue={otsFilter} allFilter={ot} type={'Offices de tourismes'} setFilterValue={setOtsFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>
                    <div onClick={() => setFilterId(4)}>
                        <Filter id={4} filterId={filterId} setFilterId={setFilterId} filterValue={EstablishmentsFilter} allFilter={etablishment} type={'Établissements'} setFilterValue={setEstablishmentsFilter} setSelectedTerritoires={setSelectedTerritoires} selectedTerritoires={selectedTerritoires}></Filter>
                    </div>

                <div className="border-b border-neutral-300 pb-2" onClick={() => setFilterId(null)}>
                    <Text className="mt-6 mb-3" size="sm">Période :</Text>
                    <DateRangePicker
                        startDate={startDate} 
                        startDateId="inputStartDate"
                        endDate={endDate} 
                        endDateId="inputEndDate"
                        //@ts-ignore
                        onDatesChange={({ startDate, endDate }) => {
                        setStartDate(startDate), setEndDate(endDate);
                        }} 
                        focusedInput={focusedInput} 
                        onFocusChange={(focusedInput: any) => setFocusedInput(focusedInput)}
                        numberOfMonths={1}
                        startDatePlaceholderText={'Début'}
                        endDatePlaceholderText={'Fin'}
                        customArrowIcon={'-'}
                        openDirection={'up'}
                        isOutsideRange={() => false}
                        customInputIcon={<i className="fa-light fa-calendar text-sm"></i>}
                        inputIconPosition="after"
                        displayFormat="DD/MM/YYYY"
                        appendToBody={true}
                    />
                </div>
            

            </div>
            
            
            
            <Button 
                variant="secondary" 
                icon="fa-solid fa-minus" 
                iconSide="left"
                className="mt-4"
                onClick={() => apiData()}
                >
                    Filtrer les résultats
            </Button>

            {lastSubmission && <Text weight={400} size="sm" color="neutral-500" className="mt-auto pt-4">Dernière mise à jour : {lastSubmission}</Text>}
        </div>
    )
}


export default Filters