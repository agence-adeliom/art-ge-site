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


const Filters = ({setTerritoryScore, filters} : {
    setTerritoryScore: Function,
    filters: any
}) => {

    const [departments, setdepartments] = useState()

    useEffect(() => {
        if(filters) {
            setdepartments(filters.departments)
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
   
    console.log(filterStartDate, filterEndDate)


    return (
        <div className="flex flex-col min-h-full">
            <a href="https://www.art-grandest.fr/" target='_blank' >
                <img src={Logo} alt="Logo ART GE" className=""/>
            </a>
            <div className="relative">
                <Text color="neutral-700" className="mt-12" weight={400}>
                    Filtrer par :
                </Text>

                <Filter filterValue={departments} type={'Territoires'} setFilterValue={setdepartments}></Filter>
                <Filter filterValue={departments} type={'Territoires'} setFilterValue={setdepartments}></Filter>
                <Filter filterValue={departments} type={'Territoires'} setFilterValue={setdepartments}></Filter>

                <div className="border-b border-neutral-300 pb-2">
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
               // onClick={() => setTerritoryScore(territories)}
                >
                    Filtrer les résultats
            </Button>

            <Text weight={400} size="sm" color="neutral-500" className="mt-auto pt-4">Dernière mise à jour : 17.01.2024</Text>
        </div>
    )
}


export default Filters