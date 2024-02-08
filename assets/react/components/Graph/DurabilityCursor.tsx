import React from "react";
import { Text } from "@components/Typography/Text";


const DurabilityCursor = () => {
    return (
        <div className="flex gap-4 items-center mt-10 print:hidden">
            <div className="w-10 h-1 border-b border-neutral-500 border-dashed"></div>
            <Text size={'sm'} color={'neutral-700'}>Seuil de durabilité : <span className="ml-2 font-bold">33</span>/100</Text>
        </div>
    )
}

export default DurabilityCursor