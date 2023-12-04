import React from "react";
import Header from "@components/Navigation/Header";

const Form = ({questions} : {
    questions: object[]
}) => {
    let allQuestions = questions
    console.log(allQuestions)

    return (
        <>
        <Header step={1} title={'Biodiversité et conservation de la Nature sur site'}></Header>
            <div>
                
                {/* {questions.questions.map((item : any, i : number) => (
                    <div key={i}>
                        <p>{item.name}</p>
                        <p>{item.id}</p>
                    </div>
            ))} */}
            </div>
        </>
       
    )
}

export default Form