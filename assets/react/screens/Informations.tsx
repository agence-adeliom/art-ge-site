import React, {useState} from 'react';
import Header from '@components/Navigation/Header';
import InfoImage from '@images/informations-image.jpeg';
import { Heading } from '@components/Typography/Heading'
import { Text } from '@components/Typography/Text'
import { Button } from '@components/Action/Button'

import { object, string, number, InferType } from 'yup';

const Informations = () => {
    
    const data = {
        firstname: '',
        lastname: '',
        email: '',
        tel: ''
    }

    //const user = await userSchema.validate(await fetchUser());

    //type User = InferType<typeof userSchema>;

    

    const [userData, setUserData] = useState(data);
    const [legalChecked, setLegalChecked] = useState(false);
    
    const {firstname, lastname, email, tel} = userData;

    let userSchema = object({
        firstname: string().required(),
        lastname: string().required(),
        email: string().email(),
        tel: string().required()
    })
    

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setUserData({...userData, [event.target.id]: event.target.value })
    }

   const acceptLegal = (event : React.ChangeEvent<HTMLInputElement>) => {
       setLegalChecked(event.target.checked)
   }

   const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
       event.preventDefault();
       userSchema.isValid({
        firstname: firstname,
        lastname: lastname,
        email: email,
        tel: tel,
      })
      .then(function (valid) {
        valid; 
        console.log(valid)
      });
   }
   


    const inputClass = 'border-0 border-b border-neutral-500 block w-full mt-4 pb-2 focus:ring-0 focus:border-primary-600'
    
    return (
        <div className="">
            <Header></Header>
            <div className="container grid grid-cols-12 gap-6 h-[calc(100vh-108px)] ">
                <div className="col-span-7 flex items-center py-10">
                    <form>
                        <Heading variant="display-4">Pour commencer</Heading>
                        <Text className="mt-6" color="neutral-500" weight={400} size="sm">Renseignez ces informations afin que nous puissions vous identifier.</Text>
                        <div className="flex gap-6 w-full mt-8">
                            <div className="w-full md:w-1/2">
                                <label className="block" htmlFor="firstname">Prénom</label>
                                <input onChange={handleChange} value={firstname} className={inputClass} id="firstname" placeholder="Ex : Julie" type="text" name="firstname" ></input>
                            </div>
                            <div className="w-full md:w-1/2">
                                <label className="block" htmlFor="lastname">Nom</label>
                                <input onChange={handleChange} value={lastname} className={inputClass} id="lastname" placeholder="Ex : Dupont" type="text" name="lastname" ></input>
                            </div>
                        </div>
                        <div className="flex gap-6 w-full mt-8">
                            <div className="w-full md:w-1/2">
                                <label className="block" htmlFor="tel">Téléphone</label>
                                <input onChange={handleChange} value={tel} className={inputClass} id="tel" placeholder="Ex : 0612345678" type="tel" name="tel" ></input>
                            </div>
                            <div className="w-full md:w-1/2">
                                <label className="block" htmlFor="email">Email</label>
                                <input onChange={handleChange} value={email} className={inputClass} id="email" placeholder="Ex : julie.dupont@mail.com" type="email" name="email" ></input>
                            </div>
                        </div>
                        <div className="flex gap-2 mt-8">
                            <input type="checkbox" name="legal" id="legal" className="checkbox" onChange={acceptLegal} />
                            <Text weight={400} color="neutral-800"><label htmlFor="legal">J’accepte que mes données soient transmises à l’ART GE et à ses partenaires. Pour en savoir plus, consultez la <a href="#" className="classic-link">politique de confidentialité</a></label></Text>
                        </div>

                        <Button size="lg" className="mt-8" 
                        disabled={firstname === '' || lastname === '' || email === '' || tel === '' || legalChecked === false ? true : false} 
                        onClick={(event) => handleSubmit(event)}
                       >
                            Suivant
                        </Button>
                        
                    </form>
                </div>
                <div className="bg-neutral-600 col-start-9 col-span-4 containerBleed relative">
                    <img src={InfoImage} alt="image de paysage" className="absolute object-cover w-full h-full"></img>
                </div>
            </div>
        </div>
    )
}

export default Informations