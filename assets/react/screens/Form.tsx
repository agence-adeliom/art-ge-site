import React, { useEffect, useState } from 'react';
import Header from '@components/Navigation/Header';
import { Heading } from '@components/Typography/Heading';
import formImage1 from '@images/form-image-1.jpeg';
import { Button } from '@components/Action/Button';
import { Text } from '@components/Typography/Text';
import Confirmation from '@screens/Confirmation';
import { ConfirmationAnim } from '@components/Animation/Confirmation';

const inputContainerClass = 'group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50 py-4 px-3';

const Form = ({ questions }: { questions: object[] }) => {
  const value = window.localStorage.getItem('allQuestions');

  const valueParse = JSON.parse(value!);

  const [allQuestions, setAllQuestions] = useState(valueParse.questions);

  const [sticky, setSticky] = useState(false);

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

  const [allAnswerArray, setAllAnswerArray] = useState([{}]);

  // Init Results answer with value = false
  let results: any = [];
  if (actualQuestion) {
    results = actualOptions
      ? Object.values(actualOptions).map((option: any, index = 0) => {
          return {
            id: option['id'],
            value: false,
            index: index,
          };
        })
      : false;
  }


  const [finish, setIsFinish] = useState(false);
  const nextStep = () => {
    if (questionStep < allQuestions.length - 1) {
      setQuestionStep(questionStep + 1)
    } else setIsFinish(true)
  }

  const isActiveClass = 'is-active';
  //Reset class active à remanier
   const resetClass = () => {
    let inputContains = document.querySelectorAll(`.labelContainer`)
    Object.values(inputContains!).map((option: any, index = 0) => {
     if (option.classList.contains(isActiveClass)) {
      option.querySelector('input').checked = false
      option.classList.remove(isActiveClass);
     }
    })
    }
  
  // Affect Answer state with Results => value = false
  const [answer, setAnswer] = useState({});
  if (results) {
    useEffect(() => {
      setAnswer(results);
    }, [actualOptions]);
  }

  // Add class active + set answer value true
  const handleChange = (e: any) => {
    let id = e.target.id;
    const answerArray = Object.values(answer);
    if (e.target.checked) {
      answerArray.find((answerItem: any) => {
        answerItem['id'] == id
          ? setAnswer({
              ...answer,
              [JSON.parse(answerItem['index'])]: {
                id: JSON.parse(id),
                value: true,
                index: answerItem['index'],
              },
            })
          : null;
      });
      e.target.parentNode.classList.add(isActiveClass);
    } else {
      answerArray.find((answerItem: any) => {
        answerItem['id'] == id
          ? setAnswer({
              ...answer,
              [JSON.parse(answerItem['index'])]: {
                id: JSON.parse(id),
                value: false,
                index: answerItem['index'],
              },
            })
          : null;
      });
      e.target.parentNode.classList.remove(isActiveClass);
    }
  };

console.log(allAnswerArray)


  return (
    <>
      <div>
        <div className="relative z-10">
          <Header
            step={questionStep}
            totalStep={15}
            title={'Biodiversité et conservation de la Nature sur site'}
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
          <form className="h-full w-full pl-1 flex">
            <div>
              <Button
                variant="textOnly"
                icon={'fa-chevron-left'}
                iconSide="left"
                weight={600}
              >
                Retour
              </Button>
              {actualQuestion != null ? (
                <div
                  className={`${
                    sticky
                      ? `max-h-0 h-0 opacity-0`
                      : `min-h-auto h-fit opacity-100`
                  } tansition-all duration-500 overflow-hidden`}
                >
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
                              <input type="checkbox" id={option.id} name={option.id} onChange={e => {handleChange(e);} } className={`formCheckbox  ml-3 my-6'`}></input>
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
                  onClick={event => {
                    event.preventDefault(),
                      nextStep(),
                      console.log('answer', answer);
                      setAllAnswerArray([...allAnswerArray, answer]);
                      resetClass();
                  }}
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

        
        <ConfirmationAnim isVisible={finish}>
          <Confirmation
            link=""
            title="Merci pour ces informations."
            subTitle="Parlons à présent de vos actions..."
          ></Confirmation>
        </ConfirmationAnim>
      </div>
    </>
  );
};

export default Form;
