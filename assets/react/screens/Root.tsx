import React, {useState} from 'react';
import  { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Home from '@screens/Home';
import Informations from '@screens/Informations';
import Form from '@screens/Form';
import { QuestionsContext, UserContext } from '@components/Context/Context';

function App() {
    let initialQuestions : any = []
    const [questions, setQuestions] = useState(initialQuestions);

    const data = {
      firstname: '',
      lastname: '',
      email: '',
      tel: ''
    }
    const [userData, setUserData] = useState<any>(data);
    return (
      <>
        
        <Router>
  
          <Routes>
         
            <Route path="/" element={<Home/>} />
              <Route 
                path="/informations" 
                element={
                  <QuestionsContext.Provider 
                    value={{questions, setQuestions, initialQuestions}}>
                       <UserContext.Provider value={{userData : userData,  data : data,  setUserData : setUserData}}>
                        <Informations/>
                      </UserContext.Provider>
                      
                  </QuestionsContext.Provider>} 
                />
            
            
            <Route path="/form" element={<Form
            questions={questions}/>} />
            <Route path="*" element={<Home/>}/>
          </Routes>
         
  
        
        
        </Router>
  
        
      </>
    );
  }
  
  export default App;