import React, {useState} from "react"
import Logo from '@images/logo/logo.png';
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';
import Filter from '@components/Filters/Filter';
import { Button } from '@components/Action/Button'; 



const Filters = ({setTerritoryScore} : {
    setTerritoryScore: Function,
}) => {

    const [territories, setTerritories] = useState([''])
    
    return (
        <div>
            <a href="https://www.art-grandest.fr/" target='_blank' >
                <img src={Logo} alt="Logo ART GE" className=""/>
            </a>
            <div className="relative">
                <Text color="neutral-700" className="mt-12" weight={400}>
                    Filtrer par :
                </Text>

                <Filter filterValue={territories} setFilterValue={setTerritories}></Filter>
                
            </div>
            
            <Button 
                variant="secondary" 
                icon="fa-solid fa-minus" 
                iconSide="left"
                onClick={() => setTerritoryScore(territories)}
                >
                    Filtrer
            </Button>
        </div>
    )
}


export default Filters