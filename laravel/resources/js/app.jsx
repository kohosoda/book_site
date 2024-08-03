/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';

// BootStrap
import 'bootstrap/dist/css/bootstrap.min.css';

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import ReactDOM from "react-dom/client";
import { useState } from 'react';
import { BrowserRouter, Routes, Route, useNavigate } from 'react-router-dom';
import SearchBar from './components/SearchBar';
import BookList from './components/BookList';
import BookDetails from './components/BookDetails';

function App() {
    const [searchResults, setSearchResults] = useState([]);
    const navigate = useNavigate();

    const handleSearch = async (searchKeyword) => {
        const encodedKeyword = encodeURIComponent(searchKeyword);
        const raws = 20;
        const path = `/api/books/search?keyword=${encodedKeyword}&raws=${raws}`;
        const response = await fetch(path);
        const data = await response.json();
        
        if (data.status === "success") {
            const books = data.books;
            setSearchResults(books);
            navigate('/');
        } else {
            console.log(data.error || 'エラーが発生しました。');
        }
    }

    return (
        <>
            <div className="container mx-auto p-4">
                <h1 className="text-3xl font-bold mb-4">Book Search App</h1>
                <SearchBar onSearch={handleSearch}/>
            </div>
            <Routes>
                <Route path="/" element={<BookList books={searchResults} />} />
                <Route path="/books/:id" element={<BookDetails />} />
            </Routes>
        </>
    );
}

const root = ReactDOM.createRoot(document.getElementById("app"));
root.render(
    <BrowserRouter>
        <App />
    </BrowserRouter>
);