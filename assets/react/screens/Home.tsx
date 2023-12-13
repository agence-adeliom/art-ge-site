import React, { useEffect } from 'react';
import Logo from '@images/logo/logo.svg';
import lightBulbOn from '@icones/lightbulb-on.svg';
import { Heading } from '@components/Typography/Heading';
import { Text } from '@components/Typography/Text';
import { Button } from '@components/Action/Button/Button';
import Footer from '@components/Navigation/Footer';
import Aside from '@components/Content/Aside';
import useReponseData, {
  useWizard,
} from '@hooks/useReponseData/useReponseData';
import { useNavigate } from 'react-router-dom';
import { RoutePaths } from '@react/config/routes';

const Home = () => {
  const { getStoredReponse } = useWizard();
  const navigate = useNavigate();
  const handleNavigation = () => {
    const savedState = getStoredReponse();
    if (savedState) {
      const shouldPreFill = window.confirm(
        'On dirait que vous avez déjà commencé à remplir le formulaire. Souhaitez-vous reprendre là où vous en étiez ?',
      );
      console.log(savedState);
      console.log(shouldPreFill);
      if (!shouldPreFill) {
        // clearWizard();
      } else {
        //navigate(savedState.lastStep.path);
      }
    } else {
      navigate(RoutePaths.INFO);
    }
  };

  return (
    <div className="w-screen overflow-hidden">
      <div className="h-screen overflow-auto">
        <div className="container">
          <div className="h-screen w-full grid grid-cols-12 auto-rows-min">
            <div className="col-span-full max-lg:mb-10 lg:col-span-7 mt-20">
              <img className="w-[282px] h-[93px]" src={Logo} alt=""></img>
              <div className="flex flex-col gap-4">
                <Heading variant="display-2" className="mt-12">
                  Bienvenue sur notre calculateur tourisme durable
                </Heading>
                <Text color="neutral-700">
                  L’Agence Régionale du Tourisme Grand Est vous invite à
                  compléter ce questionnaire pour évaluer votre niveau
                  d’engagement durable, connaître vos points forts ainsi que vos
                  axes d’amélioration.
                </Text>
              </div>

              <div className="border p-4 border-secondary-600 mt-10">
                <div className="flex items-center gap-2 mb-2">
                  <img src={lightBulbOn} alt="icon ampoule" />
                  <Text weight={600}>Avant de vous lancer</Text>
                </div>
                <ul className="list-disc list-inside marker:text-secondary-800">
                  <li className="font-normal">
                    Un{' '}
                    <strong>
                      diagnostic de votre engagement dans la transition
                    </strong>
                  </li>
                  <li className="font-normal">
                    <strong>Gratuit</strong>, facile à prendre en main,{' '}
                    <strong>sans engagement</strong> avec un résultat{' '}
                    <strong>immédiat</strong>.
                  </li>
                  <li className="font-normal">
                    <strong>Accès à des ressources</strong> pour continuellement
                    optimiser vos pratiques.
                  </li>
                </ul>
              </div>

              <Button
                size="lg"
                className="mt-8"
                icon="fa-minus"
                iconSide="left"
                onClick={handleNavigation}
              >
                Commencer
              </Button>

              <div className="mt-8">
                <Text weight={600}>Des questions ?</Text>
                <Text>
                  Retrouvez plus d’informations sur ce questionnaire dans notre{' '}
                  <a href="#" className="classic-link">
                    FAQ
                  </a>
                </Text>
              </div>
            </div>

            <Aside></Aside>

            <Footer></Footer>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Home;
