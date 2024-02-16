import React, { MouseEvent, useEffect, useRef, useState } from 'react';
import Header from '@components/Navigation/Header';
import { Heading } from '@components/Typography/Heading';
import formImage1 from '@images/form-image-1.jpeg';
import { Button } from '@components/Action/Button';
import { Text } from '@components/Typography/Text';
import Confirmation from '@screens/Confirmation';
import { ConfirmationAnim } from '@components/Animation/Confirmation';
import { useWizard } from '@hooks/useWizard';
import { serialize } from 'object-to-formdata';
import { SubmitHandler, useForm } from 'react-hook-form';
import { useNavigate } from 'react-router-dom';
import { RoutePaths } from '@react/config/routes';
import { cx } from 'class-variance-authority';
import Resultats from "@screens/Resultats";
import AsideForm from '@components/Content/AsideForm';


const inputContainerClass =
  'group trans-default lg:hover:bg-tertiary-200 is-active:border-primary-600 is-active:bg-primary-50 py-4 px-3';

const isActiveClass = 'is-active';

interface singleOptionProps {
  id: string;
  libelle: string;
  slug: string;
}

interface optionsObjectProps {
  [key: string]: singleOptionProps;
}

interface questionProps {
  choices: optionsObjectProps;
  id: string;
  libelle: string;
  thematique: {
    id: number;
    name: string;
    slug: string;
  };
}

const Form = () => {
  const navigate = useNavigate();
  const {
    feedRawFormAndGoToNextStep,
    getStoredWizard,
    wizard,
    step,
    setStep,
    clearWizard,
  } = useWizard();

  const [allQuestions, setAllQuestions] = useState<questionProps[]>([]);
  const [sticky, setSticky] = useState<boolean>(false);
  const [showConfirm, setShowConfirm] = useState<boolean>(false);
  const [formCompleted, setFormCompleted] = useState<boolean>(false);
  const [itemsSelected, setItemsSelected] = useState<string[]>([]);
  const [actualQuestion, setActualQuestion] = useState<questionProps>();

  const form = useRef<HTMLDivElement | null>(null);

  const {
    handleSubmit,
    watch,
    trigger,
    register,
    setValue,
    formState: { isValid },
  } = useForm();

  const handleBack = (e: MouseEvent<HTMLElement>) => {
    e.preventDefault();

    //Si le step est supérieur à 0 alors on revient sur à la question précédente, sinon on redirige sur la dernière étape de /informations en forçant le step à 4
    if (step > 0) {
      setStep(step - 1);
    } else {
      navigate(RoutePaths.INFO);
      setStep(4);
    }
  };

  //Récupère les options sélectionnées pour la question actuelle si présentes dans le local storage
  const getItemSelectedFromStorage = (questionID: string) => {
    const rawForm = wizard?.reponse?.rawForm;
    if (rawForm && rawForm[questionID]) {
      return Object.keys(rawForm[questionID].answers);
    } else {
      return [];
    }
  };

  const onSubmit: SubmitHandler<any> = data => {
    const isLastStep = step === allQuestions.length - 1;

    isLastStep && setFormCompleted(true);

    //Conversion de l'array d'ID en un objet avec les ID en clé et la valeur "on"
    const answerObject = data[actualQuestion!.id.toString()].reduce(
      (acc: string[], key: number) => {
        acc[key] = 'on';
        return acc;
      },
      {},
    );

    const answerSlected = {
      [actualQuestion!.id.toString()]: {
        answers: answerObject,
      },
    };
    form.current!.scrollTo({
      top: 0,
      left: 0,
      behavior: "smooth",
    });

    feedRawFormAndGoToNextStep(answerSlected);
  };


  //Requête à l'API une fois la dernière étape remplie
  const getResults = async () => {
    const formData = serialize({ reponse: wizard?.reponse }, { indices: true });

    try {
      const response = await fetch('/api/submit', {
        body: formData,
        method: 'POST',
      });
      const results = await response.json() as {
        link: string;
        uuid: string;
        resultats: Resultats;
      };

      await setTimeout(() => {
        navigate(`${RoutePaths.RESULT_ARCHIVE}/${results.uuid}`, {state: results.resultats});
        clearWizard();
      }, 2000);
    } catch (error) {
      alert(
        'Une erreur est survenue avec vos réponses. Merci de nous contacter si le problème persiste.',
      );
      clearWizard();
      navigate(RoutePaths.HOME);
    }
  };

  useEffect(() => {
    if (allQuestions.length) {
      //Définition de la question actuelle dans un state
      allQuestions.length && setActualQuestion(allQuestions[step]);

      //Mise à jour des options sélectionnées dans le state
      setItemsSelected(
        getItemSelectedFromStorage(allQuestions[step]?.id.toString()),
      );

      //Mise à jour des valeurs dans le formulaire
      setValue(
        `${allQuestions[step]?.id}`,
        getItemSelectedFromStorage(allQuestions[step]?.id.toString()),
      );
      trigger();
    }
  }, [allQuestions, step]);

  useEffect(() => {
    //Mise à jour du state des options sélectionnées à chaque modification du formulaire
    actualQuestion && setItemsSelected(watch(`${actualQuestion!.id}`));
  }, [watch()]);

  useEffect(() => {
    //Si le formulaire est completé, on envoie les données
    formCompleted && setShowConfirm(true);
    formCompleted && getResults();
  }, [formCompleted]);

  useEffect(() => {
    //Récupération des questions dans le local storage
    const value = window.localStorage.getItem('allQuestions');
    value && setAllQuestions(JSON.parse(value).questions);
    !value && navigate(RoutePaths.HOME);
    wizard.step.path !== RoutePaths.FORM && navigate(RoutePaths.INFO);

    //Gestion du menu sticky
    const handleScroll = (form: any) => {
      if (form.scrollTop > 0) {
        setSticky(true);
      } else {
        setSticky(false);
      }
    };

    if (form && form.current) {
      form.current.addEventListener('scroll', () => handleScroll(form.current));
      return function cleanup() {
        form.current &&
          form.current.removeEventListener('scroll', handleScroll, false);
      };
    }
  }, []);

  return (
    <>
      <div>
        <div className="relative z-10">
          <Header
            step={step}
            totalStep={15}
            title={actualQuestion ? actualQuestion!.thematique.name : null}
          ></Header>
        </div>

        <div
          className={` ${
            sticky ? `z-30 !translate-y-0 !top-0 opacity-100` : `z-0 opacity-0`
          } -top-20 w-full absolute  left-0 tansition-all duration-500 bg-white`}
        >
          <div
            className={` w-full bg-white absolute top-0 left-0 min-h-[93px] flex items-center`}
          >
            <div className="container">
              {actualQuestion && (
                <div className="w-10/12">
                  <Heading variant="display-5" className="my-4">
                    {actualQuestion.libelle}
                  </Heading>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      <div className="container max-lg:pb-6 grid grid-cols-12 gap-6 md:h-[calc(100vh-93px)] relative">
        <div
          className="col-span-full lg:col-span-8 flex items-center md:py-10 overflow-auto"
          ref={form}
        >
          <form
            onSubmit={handleSubmit(onSubmit)}
            className="h-full w-full pl-1 flex"
          >
            {step <= allQuestions.length &&
              allQuestions.map((question: any, key: number) => {
                return key === step ? (
                  <div key={key}>
                    <Button
                      variant="textOnly"
                      icon={'fa-chevron-left'}
                      iconSide="left"
                      className="!w-fit"
                      weight={600}
                      onClick={event => handleBack(event)}
                    >
                      Retour
                    </Button>
                    {question != null ? (
                      <div>
                        <Heading variant="display-5" className="my-4">
                          {question.libelle}
                        </Heading>
                      </div>
                    ) : (
                      false
                    )}
                    <Text color="neutral-700" className="mb-6">
                      Cochez les actions mises en place dans votre
                      établissement.
                    </Text>
                    <div className="pb-10">
                      {question.choices &&
                        Object.values(
                          question.choices as optionsObjectProps,
                        ).map((option: singleOptionProps, key: number) => {
                          return (
                            <div key={key}>
                              <label
                                className={cx(
                                  inputContainerClass,
                                  itemsSelected.length &&
                                    itemsSelected.includes(
                                      option.id.toString(),
                                    ) &&
                                    isActiveClass,
                                  'labelContainer w-full cursor-pointer justify-end flex-row-reverse flex border-b border-gray-400 gap-2',
                                )}
                              >
                                <Text
                                  as="span"
                                  weight={400}
                                  color="neutral-800"
                                >
                                  {option.libelle}
                                </Text>
                                <input
                                  type="checkbox"
                                  value={`${option.id}`}
                                  className={`formCheckbox rounded md:ml-3 w-[22px] h-[22px] my-6'`}
                                  {...register(`${question.id}`, {
                                    validate: value => value?.length > 0,
                                  })}
                                ></input>
                              </label>
                            </div>
                          );
                        })}  
                      <Button
                        icon="fa-minus"
                        className="my-10"
                        iconSide="left"
                        disabled={!isValid}
                        weight={600}
                      >
                        Suivant
                      </Button>
                    </div>
                  </div>
                ) : null;
              })}
          </form>
        </div>
        <AsideForm
        thematique={actualQuestion ? actualQuestion!.thematique.slug : 'biodiversite-et-conservation-de-la-nature-sur-site'}
        ></AsideForm>

        <ConfirmationAnim isVisible={showConfirm}>
          <Confirmation
            title="Merci pour vos réponses."
            subTitle="Nous calculons vos résultats..."
          ></Confirmation>
        </ConfirmationAnim>
      </div>
    </>
  );
};

export default Form;
