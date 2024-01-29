import React, {useState} from "react";
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';
import { Checkbox } from "@components/Fields/Checkbox";


const inputContainer = `group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50`
const Filter = ({filterValue, setFilterValue, type} : {
    filterValue: any,
    setFilterValue: Function,
    type: string
}) => {
    const [openModal, setOpenModal] = useState(false)

    const [filterChecked, setFilterChecked] = useState<string[]>([])


    const handleCheckbox = (e : any) => {
        e.stopPropagation()
        e.target.parentNode.classList.toggle('is-active')
        //console.log(e.target.checked)
        if (e.target.checked) {
            //console.log('check',e.target.value)
            setFilterChecked([...filterChecked, e.target.id])
        } else {
            const index = filterChecked.indexOf(e.target.id)
            filterChecked.splice(index, 1)
        }

    }
            //console.log(filterChecked)

    return (
        <div className="mt-4">
            <p>Territoires :</p>
            <div className="mt-3" onClick={(e) => {e.stopPropagation(); setOpenModal(!openModal)}}>
                <div className="flex items-center gap justify-between border-b border-neutral-300 pb-2 pt-3 pr-4">
                    <Text>Tous les territoires</Text>
                    <Icon icon="fa-solid fa-chevron-right" size={'sm'}></Icon>
                </div>
            </div>
            { 
                <div className={`absolute top-0 left-[calc(100%+40px)] w-[400px] bg-white shadow-lg h-[300px] overflow-scroll ${openModal ? 'opacity-100' : 'opacity-0 pointer-events-none'}`}> 
                 <div onClick={() => setOpenModal(false)} className="absolute top-4 right-4 cursor-pointer"><Icon icon="fa-solid fa-xmark"></Icon></div>
                    <Text size="lg" className="p-4" weight={500}>{type}</Text>
                    <div className="flex flex-col mt-2">
                    
                    {filterValue && Object.values(filterValue).map((el : any, key : any) => (
                        <div key={key} className={`flex items-center ${inputContainer}`} onClick={(e) => {handleCheckbox(e)}}>
                            <input type="checkbox"  className={`filterCheckbox rounded m-2`} id={el.name}></input>
                            <label className="w-full py-2" onClick={(e) => e.stopPropagation()} htmlFor={el.name}>{el.name}</label>
                        </div>
                    ))}
                </div>
                </div>
            }
        </div>
    )
}

export default Filter