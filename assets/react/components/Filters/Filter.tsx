import React, {useEffect, useState} from "react";
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';
import { Checkbox } from "@components/Fields/Checkbox";
import { SelectedTerritoires } from "@react/types/Dashboard";


const inputContainer = `group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50`
const Filter = ({id, setFilterId, filterId, setFilterValue, type, allFilter, setSelectedTerritoires, selectedTerritoires} : {
    filterValue: any,
    id: number,
    setFilterId: Function,
    filterId?: number | null,
    setFilterValue: Function,
    type: string,
    allFilter: any,
    setSelectedTerritoires: Function,
    selectedTerritoires: SelectedTerritoires,
}) => {

    

    const [openModal, setOpenModal] = useState(false)

    const [filterChecked, setFilterChecked] = useState<string[]>([])
    
    useEffect(() => {
        if(filterChecked) {
            setFilterValue(filterChecked)
        }
    }, [filterChecked])

    useEffect(() => {
        if(filterId === id) {
            setOpenModal(true)
        } else {
            setOpenModal(false)
        }
    }, [filterId])

    const handleCheckbox = (e : any) => {
        e.stopPropagation()
        e.target.parentNode.classList.toggle('is-active')

        const targetSlug = e.target.id;
        const type = e.target.dataset.type;
        let realType;
        if (type) {
            if (type === 'Territoires' ){
                realType = 'tourisms';
            } else if (type === 'Départements') {
                realType = 'departments';
            } else if (type === 'Offices de tourismes') {
                realType = 'ots';
            } else if (type === 'Établissements') {
                realType = 'typologies';
            }
        }
        
        if (e.target.checked) {
            if (realType !== undefined && ! selectedTerritoires[realType].find((slug) => slug === targetSlug)) {
                selectedTerritoires[realType].push(targetSlug)
                setSelectedTerritoires(selectedTerritoires)
            }
            
            setFilterChecked([...filterChecked, targetSlug])
        } else {
            const index = filterChecked.indexOf(targetSlug)
            filterChecked.splice(index, 1)
            if (realType !== undefined) {
                const index = selectedTerritoires[realType].findIndex((slug) => slug === targetSlug);
                if (index > -1) {
                    selectedTerritoires[realType].splice(index, 1);
                    setSelectedTerritoires(selectedTerritoires)
                }
            }
        }

    }

    return (
        <div className="mt-4"
        >
            <Text color="black" size="sm">{type} :</Text>
            <div className="mt-3" 
            >

                <div className="flex items-center gap justify-between border-b border-neutral-300 pb-2 pt-3 pr-4">
                    <Text color="neutral-700" size="sm" className="text-ellipsis whitespace-nowrap w-full overflow-hidden">
                        {filterChecked.length > 0 ? filterChecked.map((el : any) => `${el}, `) : `Tous les ${type}`}</Text>
                    <Icon icon="fa-solid fa-chevron-right" size={'sm'}></Icon>
                </div>
            </div>
            { 
                <div className={`fixed top-0 left-[320px] w-[400px] bg-white shadow-lg h-screen overflow-scroll ${openModal ? 'opacity-100' : 'opacity-0 pointer-events-none'}`}> 
                    <div onClick={(e) => {e.stopPropagation(),setFilterId(null)}} className="absolute top-4 right-4 cursor-pointer">
                        <Icon icon="fa-solid fa-xmark"></Icon>
                    </div>
                    <Text size="lg" className="p-4" weight={500}>{type}</Text>
                    <div className="flex flex-col mt-2">
                    
                    {allFilter && Object.values(allFilter).map((el : any, key : any) => (
                        <div key={key} className={`flex items-center ${inputContainer}`} onClick={(e) => {handleCheckbox(e)}}>
                            <input type="checkbox"  className={`filterCheckbox rounded m-2`} id={el.slug} data-type={type}></input>
                            <label className="w-full py-2" onClick={(e) => e.stopPropagation()} htmlFor={el.slug}>{el.name}</label>
                        </div>
                    ))}
                </div>
                </div>
            }
        </div>
    )
}

export default Filter