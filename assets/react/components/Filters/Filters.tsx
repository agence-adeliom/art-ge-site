import React, {useEffect, useState} from "react"
import Logo from '@images/logo/logo.png';
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';
import Filter from '@components/Filters/Filter';
import { Button } from '@components/Action/Button'; 



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

   if (departments) {
    console.log(departments)
   }
    return (
        <div>
            <a href="https://www.art-grandest.fr/" target='_blank' >
                <img src={Logo} alt="Logo ART GE" className=""/>
            </a>
            <div className="relative">
                <Text color="neutral-700" className="mt-12" weight={400}>
                    Filtrer par :
                </Text>

                <Filter filterValue={departments} type={'Départements'} setFilterValue={setdepartments}></Filter>
            </div>

            
            
            <Button 
                variant="secondary" 
                icon="fa-solid fa-minus" 
                iconSide="left"
               // onClick={() => setTerritoryScore(territories)}
                >
                    Filtrer
            </Button>
        </div>
    )
}


export default Filters