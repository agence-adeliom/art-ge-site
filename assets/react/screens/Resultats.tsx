import React, { useEffect } from 'react';
import Header from '@components/Navigation/Header';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button';
import ProgressBar from '@components/ProgressBar/ProgressBar';
import ResultCard from '@components/Cards/ResultCard';
import { AnimatePresence } from 'framer-motion';
import FooterResult from '@components/Navigation/FooterResults';
import Cta from '@components/Content/Cta';
import {useLocation} from 'react-router-dom';

export interface Choice {
  name: string;
  slug: string;
}

interface Score {
  name: string;
  slug: string;
  points: number;
  total: number;
  percentage: number;
  chosenChoices: Choice[];
  notChosenChoices: Choice[];
}

interface Resultats {
  reponsePercentage: number;
  submitDate: string;
  scores: Score[];
}

declare global {
  interface Window {
    resultats: Resultats;
  }
}

const Resultats = () => {
  const location = useLocation();
  let resultats;
  if (location.state !== null){
    resultats = location.state as Resultats;
  } else {
    resultats = window.resultats;
  }
  let heading = 'Félicitations !';
  let subHeading = 'Vos engagements font la différence.';
  let text = 'Découvrez votre avancement thématique par thématique et accédez à des ressources pour faire évoluer les pratiques de votre établissement.';
  if (resultats.reponsePercentage >= 0 && resultats.reponsePercentage <= 32) {
      heading = 'Merci !';
      subHeading = 'Ce résultat montre votre intérêt et votre motivation';
      text = 'Regardez vos résultats dans le détail, thématique par thématique ainsi que toutes les pistes d’amélioration qui s’offrent à vous.';
  } else if (resultats.reponsePercentage > 32 && resultats.reponsePercentage <= 60) {
      heading = 'Merci !';
      subHeading = 'Ce résultat montre une belle implication.';
      text = 'Regardez vos résultats dans le détail, thématique par thématique, identifiez les pistes d’amélioration et découvrez les actions à mener pour progresser davantage.';
  }

  return (
    <AnimatePresence>
      <>
        <Header
          button={{
            quitAction: false,
            name: 'Nous contacter',
            type: 'primary',
            icon: 'fa-minus',
            iconSide: 'left',
            link: '/#',
          }}
        />
        <div className="bg-primary-600">
          <div className="container grid grid-cols-12 gap-6 items-center pt-20 pb-8">
            <div className="flex flex-col gap-4 col-span-full md:col-span-8 dark">
              <Heading variant="display-2" color="white">{heading}</Heading>
              <Heading variant="display-3" color="white">{subHeading}</Heading>
              <Text size="lg" color="white" className="mt-4">{text}</Text>
              <Button
                iconSide="left"
                size={'lg'}
                variant="primary"
                className="dark"
                icon="fa-link"
                onClick={() =>
                  navigator.clipboard.writeText(window.location.href)
                }
              >
                Copier le lien
              </Button>
            </div>
            <div className="col-span-full md:col-span-4 bg-white p-10 h-fit">
              <Heading variant="display-5">Votre score</Heading>
              <Text className="font-title mb-4" size={'4xl'}>
                <span className="text-6xl">{resultats.reponsePercentage}</span> %
              </Text>
              <ProgressBar
                percentage={resultats.reponsePercentage}
              ></ProgressBar>
              <Text className="mt-4" color="neutral-700" size={'sm'}>
                {`Date de soumission : ${resultats.submitDate}`}
              </Text>
            </div>
          </div>
        </div>

        <div className="bg-primary-50 relative">
          <div className="absolute top-0 left-0 w-full h-20 bg-primary-600"></div>
          <div className="container relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6">
            {resultats.scores.map((score: Score) => (
              <ResultCard
                key={score.slug}
                percentage={score.percentage}
                title={score.name}
                chosenChoices={score.chosenChoices}
                notChosenChoices={score.notChosenChoices}
              ></ResultCard>
            ))}
          </div>
        </div>
        <div className="bg-primary-50 py-20">
          <div className="container">
            <Cta></Cta>
          </div>
        </div>

        <FooterResult></FooterResult>
      </>
    </AnimatePresence>
  );
};

export default Resultats;
