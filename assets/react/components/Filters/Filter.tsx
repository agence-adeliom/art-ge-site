import React, {useState} from "react";
import { Text } from '@components/Typography/Text';
import { Icon } from '@components/Typography/Icon';

const Filter = ({filterValue, setFilterValue} : {
    filterValue: any,
    setFilterValue: Function
}) => {
    const [openModal, setOpenModal] = useState(false)
    const [value, setValue] = useState('')
    const handleChange = (e : any) => {
        console.log(e)
        setValue(e.target.value)
        setFilterValue(e.target.value)
    }
    return (
        <div className="mt-4">
            <p>Territoires :</p>
            <div className="mt-3" onClick={(e) => {e.stopPropagation(); setOpenModal(!openModal)}}>
                <div className="flex items-center gap justify-between border-b border-neutral-300 pb-2 pt-3 pr-4">
                    <Text>Tous les territoires</Text>
                    <Icon icon="fa-solid fa-chevron-right" size={'sm'}></Icon>
                </div>
            </div>
            {openModal && 
                <div className="p-10 absolute top-0 left-[calc(100%+40px)] bg-white shadow-lg" > 
                 <div onClick={() => setOpenModal(false)}><Icon icon="fa-solid fa-xmark"></Icon></div>
                    <p>Poucentage score</p>
                    <input type="number" value={value} onChange={e => handleChange(e)}></input>
                </div>
            }
        </div>
    )
}

export default Filter