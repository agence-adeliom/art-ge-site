import React, {useEffect, useState} from "react";
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';
import { Checkbox } from "@components/Fields/Checkbox";
import { SelectedTerritoires } from "@react/types/Dashboard";


const inputContainer = `group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50`
const Filter = ({id, setOt,setFilterId, filterId, setFilterValue, type, allFilter, setSelectedTerritoires, selectedTerritoires} : {
    filterValue: any,
    id: number,
    setFilterId: Function,
    filterId?: number | null,
    setFilterValue: Function,
    type: string,
    allFilter: any,
    setSelectedTerritoires: Function,
    selectedTerritoires: SelectedTerritoires,
    setOt: Function
}) => {
    const [openModal, setOpenModal] = useState(false)

    const [filterChecked, setFilterChecked] = useState<string[]>([]);

    
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
        //prevent click on parent div 
        if ( e.target.parentNode.classList.contains('inputContainer')) {
            e.target.parentNode.classList.toggle('is-active')
       
    
        if (e.target.checked) {
            setFilterChecked([...filterChecked, e.target.id])
        } else {
            const index = filterChecked.indexOf(e.target.dataset.name);
            filterChecked.splice(index, 1)
        }

        const targetSlug = e.target.id;
        const targetName = e.target.dataset.name;
        const type = e.target.dataset.type;
        let realType: string | undefined;
        if (type) {
            if (type === 'Territoires' ){
                realType = 'tourisms';
            } else if (type === 'Départements') {
                realType = 'departments';
            } else if (type === 'Offices de tourisme') {
                realType = 'ots';
            } else if (type === 'Établissements') {
                realType = 'typologies';
            }
        }
        
        if (e.target.checked) {
            if (realType !== undefined && ! selectedTerritoires[realType].find((slug) => slug === targetSlug)) {
                selectedTerritoires[realType].push(targetSlug)
                
                setSelectedTerritoires(() => ({
                    departments: Array.from(new Set([...selectedTerritoires['departments'], ...(realType === 'departments' ? [targetSlug] : [])])),
                    ots: Array.from(new Set([...selectedTerritoires['ots'], ...(realType === 'ots' ? [targetSlug] : [])])),
                    tourisms: Array.from(new Set([...selectedTerritoires['tourisms'], ...(realType === 'tourisms' ? [targetSlug] : [])])),
                    typologies: Array.from(new Set([...selectedTerritoires['typologies'], ...(realType === 'typologies' ? [targetSlug] : [])])),
                }));
            }
            
            setFilterChecked([...filterChecked, targetName])
            
        } else if (e.target.checked === false) {
            if (realType !== undefined) {
                const index = selectedTerritoires[realType].findIndex((slug) => slug === targetSlug);
                if (index > -1) {
                    selectedTerritoires[realType].splice(index, 1);
                
                    setSelectedTerritoires(() => ({
                        departments: Array.from(new Set([...selectedTerritoires['departments']])),
                        ots: Array.from(new Set([...selectedTerritoires['ots']])),
                        tourisms: Array.from(new Set([...selectedTerritoires['tourisms']])),
                        typologies: Array.from(new Set([...selectedTerritoires['typologies']])),
                    }));
                }
            }
        }

        }
        
    }


    return (
        <div className="mt-5">
            <Text color="black" size="sm">{type} :</Text>
            <div className="mt-3" 
            >

                <div className="flex items-center gap justify-between border-b border-neutral-300 pb-2 pr-4 cursor-pointer">
                    <Text color="neutral-700" size="sm" className="text-ellipsis whitespace-nowrap w-full overflow-hidden">
                        {filterChecked.length > 0 ? filterChecked.map((el : any, index: number) => (index > 0 && index < filterChecked.length && filterChecked.length > 1 ? ', ' : '') + `${el}`) : `Tous les ${type.toLowerCase()}`}</Text>
                    <Icon icon="fa-solid fa-chevron-right" size={'sm'}></Icon>
                </div>
            </div>
                <>
                    <div className={`fixed top-0 w-full left-0 md:left-[320px] md:w-[400px] bg-white shadow-lg h-screen overflow-auto trans-default z-[200] ${openModal ? 'opacity-1 translate-x-0' : 'pointer-events-none opacity-0 translate-x-full md:-translate-x-0'}`}> 
                        <div onClick={(e) => {e.stopPropagation(),setFilterId(null)}} className="absolute top-4 right-4 cursor-pointer">
                            <Icon icon="fa-solid fa-xmark"></Icon>
                        </div>
                        <Text size="4xl" className="p-10 pb-7 font-title" weight={500}>{type}</Text>
                        <div className="flex flex-col mt-2">

                        {allFilter && Object.values(allFilter).map((el : any) => (
                            <div key={el.slug} className={`flex items-center inputContainer ${inputContainer}`} onClick={(e) => {e.stopPropagation(), handleCheckbox(e)}}>
                                <input type="checkbox"  className={`filterCheckbox rounded ml-10 m-2 cursor-pointer`} id={el.slug} data-name={el.name} data-type={type}></input>
                                <label className="w-full py-2 pr-10 text-sm cursor-pointer" onClick={(e) => e.stopPropagation()} htmlFor={el.slug}>{el.name}</label>
                            </div>
                        ))}

                        { allFilter == null &&
                            
                            <div className="px-10">
                                <Text weight={600} size={'sm'}>Il n'existe pas de {type.toLowerCase()} pour votre sélection…</Text>
                                <Text size={'sm'} className="pt-2">
                                    Désactivez ou modifiez vos filtres pour accéder à ces éléments.
                                </Text>
                            </div>
                        }
                            
                        </div>
                    </div>

                    <div 
                        onClick={(e) => {e.stopPropagation(),setFilterId(null)}}
                        className={`cursor-pointer hidden md:block fixed w-[calc(100vw-320px)] h-screen top-0 right-0 bg-black bg-opacity-50 z-[50] trans-default ${openModal ? 'opacity-1' : 'pointer-events-none opacity-0'}`}>
                    </div>
                </>
           
        </div>
    )
}

export default Filter