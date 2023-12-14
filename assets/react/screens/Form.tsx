import React, { ReactEventHandler, useEffect, useState } from 'react';
import Header from '@components/Navigation/Header';
import { Heading } from '@components/Typography/Heading';
import formImage1 from '@images/form-image-1.jpeg';
import { Button } from '@components/Action/Button';
import { Text } from '@components/Typography/Text';
import Confirmation from '@screens/Confirmation';
import { ConfirmationAnim } from '@components/Animation/Confirmation';
import useReponseData from '@hooks/useReponseData/useReponseData';
import { serialize } from 'object-to-formdata';

import { SubmitHandler, set, useForm } from 'react-hook-form';
import { Fields } from '@react/types/Fields';
import { Checkbox } from '@components/Fields/Checkbox';


const inputContainerClass = 'group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50 py-4 px-3';

const Form = ({ questions }: { questions: object[] }) => {
  const value = window.localStorage.getItem('allQuestions');

  const valueParse = JSON.parse(value!);

  const [allQuestions, setAllQuestions] = useState(valueParse.questions);

  const [sticky, setSticky] = useState(false);

  const [disabled, setDisabled] = useState(true);

  const handleScroll = (form: any) => {
    if (form.scrollTop > 0) {
      setSticky(true);
    } else {
      setSticky(false);
    }
  };

  const form = document.querySelector('#formContainer');
  form?.addEventListener('scroll', () => handleScroll(form));

  const [questionStep, setQuestionStep] = useState(0);

  const [actualQuestion, setActualQuestion] = useState();
  const [actualOptions, setActualOptions] = useState();

  if (allQuestions.length) {
    useEffect(() => {
      if (allQuestions.length !== 0) {
        setActualQuestion(allQuestions[questionStep]);
        setActualOptions(allQuestions[questionStep]['choices']);
      }
    }, [allQuestions, questionStep]);
  }


  const [finish, setIsFinish] = useState(false);

  const previewStep = (event : any) => {
    event.preventDefault();
    if (questionStep > 0) {
      setQuestionStep(questionStep - 1)
    }
  }

  const nextStep = () => {
    if (questionStep < allQuestions.length - 1) {
      setQuestionStep(questionStep + 1)
    } else {
      setIsFinish(true)
      const formData = serialize({reponse : reponse}, { indices: true });
      fetch(`/api/submit`, {
        body: formData,
        method: 'POST',
      })
      .then(
        (response : any) => {
          console.log(response.json())
        }
      )

    }
  }

const isActiveClass = 'is-active';

const { feedRawForm, reponse } = useReponseData();
const { feedRepondant } = useReponseData();

const repondant = {
  firstname: 'test',
  lastname: 'test',
  email: 'test',
  phone: 'test',
  company: 'test',
  address: 'test',
  zip: 'test',
  city: 'test',
  country: 'test',
}

let answerArray  : any = {};
const [arrayAnswer, setarrayAnswer] : Array<any> = useState([]);

  const onSubmit = (event : any) => {
    event.preventDefault()
    const form = event.target;
    const inputs = form.querySelectorAll('input[type=checkbox]')
    Object.values(inputs).map((input: any, index : number) => {
      if (input.checked) {
        setDisabled(true)
        input.parentNode.classList.remove(isActiveClass);
        input.checked = false
        
        answerArray[input['id']] = 'on'  
      }
      
    })
    

    setarrayAnswer({...arrayAnswer, 
      [JSON.parse(actualQuestion!['id'])] : {
        'answers' : answerArray
      }
        
    })
    feedRawForm(arrayAnswer)
    nextStep()
  };

  useEffect(() => {
    feedRepondant(repondant) 
  }, [])

 

  const handleActiveClass = (event : any) => {
    if (event.target.checked) {
      event.target.parentNode.classList.add(isActiveClass);
    } else {
      event.target.parentNode.classList.remove(isActiveClass);
    }
  }

  const handleDisbaled = (event : any) => {
    let inputs : any = []
    let form = document.querySelector('form');
    inputs = form?.querySelectorAll(`input[type=checkbox]:checked`);
    if (Object.values(inputs!).length > 0) {
      setDisabled(false)
    } else {
      setDisabled(true)
    }
  }

  console.log(reponse)

  return (
    <>
      <div>
        <div className="relative z-10">
          <Header
            step={questionStep}
            totalStep={15}
            title={ actualQuestion ? actualQuestion!['thematique']['name'] : null}
          ></Header>
        </div>

        <div
          className={` ${
            sticky ? `z-30 !translate-y-0 !top-0 opacity-100` : `z-0 opacity-0`
          } -top-20 w-full absolute  left-0 tansition-all duration-500 bg-white`}
        >
          <div
            className={` w-full bg-white absolute top-0 left-0 min-h-[108px] flex items-center`}
          >
            <div className="container">
              {actualQuestion != null ? (
                <div className="w-8/12">
                  <Heading variant="display-5" className="my-4">
                    {actualQuestion['libelle']}
                  </Heading>
                </div>
              ) : null}
            </div>
          </div>
        </div>
      </div>

      <div className="container max-lg:pb-6 grid grid-cols-12 gap-6 md:h-[calc(100vh-108px)] relative">
        <div
          className="col-span-full lg:col-span-8 flex items-center md:py-10 overflow-auto"
          id="formContainer"
        >
          <form onSubmit={onSubmit} className="h-full w-full pl-1 flex">
            <div>
              <Button
                variant="textOnly"
                icon={'fa-chevron-left'}
                iconSide="left"
                className="!w-fit"
                weight={600}
                onClick={(event) => previewStep(event)}
              >
                Retour
              </Button>
              {actualQuestion != null ? (
                <div>
                  <Heading variant="display-5" className="my-4">
                    {actualQuestion['libelle']}
                  </Heading>
                </div>
              ) : (
                false
              )}
              <Text color="neutral-700" className="mb-6">
                Cochez les actions mises en place dans votre établissement.
              </Text>
              <div className="pb-10">
                {actualOptions != null
                  ? (Object.values(actualOptions) as any).map(
                      (option: any, key: number) => {
                        return (
                        
                          <div key={key}>
                            <label className={`${inputContainerClass} labelContainer w-full cursor-pointer justify-end flex-row-reverse flex border-b border-gray-400 gap-2`}>
                                <Text weight={400} color="neutral-800">
                                    { option.libelle }
                                </Text>
                              <input 
                                type="checkbox" 
                                onClick={event => {handleActiveClass(event); handleDisbaled(event)}}
                                id={option.id} 
                                name={option.id} 
                                 
                                className={`formCheckbox rounded md:ml-3 w-[22px] h-[22px] my-6'`}>
                              </input>
                            </label>
                          </div>
                        );
                      },
                    )
                  : false}
                <Button
                  icon="fa-minus"
                  className="my-10"
                  iconSide="left"
                  disabled={disabled}
                  weight={600}
                >
                  Suivant
                </Button>
              </div>
            </div>
          </form>
        </div>
        <div className="bg-neutral-600 max-lg:h-32 mobileLeftBleed lg:left-0 max-lg:order-first lg:col-start-9 lg:col-span-4 containerBleed relative">
          <img
            src={formImage1}
            alt="image de paysage"
            className={`trans-default absolute object-cover w-full h-full`}
          ></img>
        </div>

        
        {/* <ConfirmationAnim isVisible={finish}>
          <Confirmation
            link=""
            title="Merci pour ces informations."
            subTitle="Parlons à présent de vos actions..."
          ></Confirmation>
        </ConfirmationAnim> */}
      </div>
    </>
  );
};

export default Form;
