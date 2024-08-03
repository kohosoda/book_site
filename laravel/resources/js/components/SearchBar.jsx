import React, { useState } from 'react';

const SearchBar = ({ onSearch }) => {
    const [keyword, setKeyword] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        onSearch(keyword);
    };

    return (
        <form onSubmit={handleSubmit} className="mb-4">
            <input
                type="text"
                value={keyword}
                onChange={(e) => setKeyword(e.target.value)}
                placeholder='検索キーワード'
                className='p-2 border rounded'
            />
            <button type="submit" className="p-2 rounded">
                検索
            </button>
        </form>
    );
}

export default SearchBar;
