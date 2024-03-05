import React from "react";
import {Text} from "@components/Typography/Text";

const Footer = () => {
    return (
         
         <>
            {/* Footer */}
            <div className="col-span-full lg:col-span-8 lg:row-start-2 text-white bg-neutral-700 h-fit py-10 lg:py-6 self-end relative">
            <div className="w-screen mobileLeftBleed lg:w-full absolute h-full lg:-left-1/2 bg-neutral-700 top-0 z-0 lg:-translate-x-[calc(49%)]"></div>
            <div className="relative z-2">
                <nav>
                    <ul className="flex items-center max-md:justify-center gap-6 text-sm font-normal flex-wrap">
                        <li><a href="https://www.art-grandest.fr/mentions-legales" target="_blank">Mentions légales</a></li>
                        <li><a href="">Gestion des cookies</a></li>
                        <li><a href="">Politique de confidentialité</a></li>
                    </ul>
                </nav>
            </div>
            <div className="flex-col md:flex-row flex items-center gap-6 justify-between mt-6 relative z-2">
                <Text color="white" weight={400} size="sm">2024 ©ARTGE - Tous droits réservés</Text>
                <div>
                    <a href="https://adeliom.com/" className="flex items-center gap-2 mr-6" target="_blank">
                        <Text weight={400} size="sm" color="white">Conception</Text>
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.9991 5.998C11.9991 2.69331 9.30576 0 5.99707 0V6.002H11.9951L11.9991 5.998Z" fill="#6E6E6E"/>
                            <path d="M3.001 6.002C4.6584 6.002 6.002 4.6584 6.002 3.001C6.002 1.34359 4.6584 0 3.001 0C1.34359 0 0 1.34359 0 3.001C0 4.6584 1.34359 6.002 3.001 6.002Z" fill="#CFCFCF"/>
                            <path d="M12.0002 5.99817H3.00122C1.35087 6.00616 0.012207 7.34482 0.012207 8.99917C0.012207 10.6535 1.35486 12.0002 3.01321 12.0002H12.0002V5.99817Z" fill="#6F6F6F"/>
                            <path d="M5.99707 5.99789C5.99707 9.30658 8.68638 11.9959 11.9951 11.9959V5.9939H5.99707V5.99789Z" fill="#373737"/>
                        </svg>
                        <Text weight={400} size="sm" color="white">Agence Adeliom</Text>
                    </a>
                </div>
            </div>
        </div>
     </>
    )
}

export default Footer;