import React from "react";

const data = {
    firstname: '',
    lastname: '',
    email: '',
    tel: ''
  }
export const QuestionsContext = React.createContext({initialQuestions : [], questions : [] , setQuestions : {}}); 
export const UserContext = React.createContext({data : data, userData : {firstname: data.firstname, lastname : data.lastname, email: data.email, tel: data.tel} , setUserData : {}}); 
