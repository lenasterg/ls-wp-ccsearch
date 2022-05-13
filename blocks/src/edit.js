/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

import {
  Placeholder,
  Button,
  TextControl,
  SelectControl,
  CheckboxControl,
} from "@wordpress/components";

import {
  Card,
  CardHeader,
  CardBody,
  CardFooter,
  __experimentalText as Text,
  __experimentalHeading as Heading,
} from "@wordpress/components";

import { useState } from "@wordpress/element";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps, BlockIcon } from "@wordpress/block-editor";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

export default function Edit() {
  const [loading, setLoading] = useState(false);
  const [query, setQuery] = useState("");
  const [apiData, setApiResultData] = useState("");
  const [selectedPic, setSelectedPic] = useState(null);
  const [searchTerm, setSearchTerm] = useState("");
  const [currentPage, setCurrentPage] = useState(1);

  const [activeSource, setActiveSource] = useState([]);
  const [sources, setsources] = useState([]);
  const [searchAllSources, setSearchAllSources] = useState(true);

  const searchPhotos = async (e, page) => {
    e.preventDefault();
    setLoading(true);
    fetch(
      `https://api.openverse.engineering/v1/images?format=json&shouldPersistImages=true&q=${searchTerm}&source=${
        activeSource ? activeSource : ""
      }&licence=BY-NC-SA&page_size=20&page=${page ? page : 1}`
    )
      .then((response) => response.json())
      .then((data) => {
        setApiResultData(data);
      })
      .then(() => setLoading(false))
      .catch((error) => {});
  };

  //get sources from the openverse api
  const getSources = async (e) => {
    setLoading(true);
    fetch(`https://api.openverse.engineering/v1/images/stats/?format=json`)
      .then((response) => response.json())
      .then((data) => {
        setsources(data);
      })
      .then(() => setLoading(false))
      .catch((error) => {});
  };

  return (
    <div {...useBlockProps()}>
      <Placeholder
        className="bg-yellow"
        icon={<BlockIcon icon="format-image" />}
        label={__("Openverse Image Block", "ls-wp-ccsearch")}
        instructions={__(
          "Quickly add openverce images in your site.",
          "ls-wp-ccsearch"
        )}
      >
        <CheckboxControl
          label="Search all image sources"
          help="Uncheck to select a provider from the list"
          checked={searchAllSources}
          onChange={(e) => {
            setSearchAllSources(!searchAllSources);
            searchAllSources ? getSources() : null;
          }}
        />
        {sources && !searchAllSources && (
          <SelectControl onChange={setActiveSource}>
            <option value="">
              {__("Search all sources", "ls-wp-ccsearch")}
            </option>
            {sources.map((provider) => (
              <option
                value={provider.source_name}
                selected={activeSource === provider.source_name ? true : false}
              >
                {provider.display_name} ({provider.media_count})
              </option>
            ))}
          </SelectControl>
        )}
        <TextControl
          label="Keyword"
          value={searchTerm}
          onChange={(value) => setSearchTerm(value)}
        />
        <Button onClick={searchPhotos}>Search</Button>
      </Placeholder>
      <div className="openverse-search-results">
        {loading ? "Loading..." : ""}
        {!selectedPic && !loading && apiData && (
          <>
            {apiData.result_count} - {apiData.page_count} - {apiData.page} -{" "}
            {apiData.page_size}
            {apiData.results.map((image) => (
              <Card key={image.id}>
                <CardBody>
                  <img
                    className="openverse-image"
                    alt={`${image.title} by ${image.provider} - ${image.license}`}
                    src={image.thumbnail}
                    width="50%"
                    height="50%"
                    onClick={() => {
                      setSelectedPic(pic);
                    }}
                  ></img>
                  <p>{image.license}</p>
                </CardBody>
              </Card>
            ))}
            {currentPage > 1 ? (
              <Button
                onClick={(e) => {
                  setCurrentPage(currentPage > 0 ? currentPage - 1 : 1);

                  searchPhotos(e, currentPage > 0 ? currentPage - 1 : 1);
                }}
              >
                Prev Page
              </Button>
            ) : null}
            <Button
              onClick={(e) => {
                setCurrentPage(currentPage + 1);

                searchPhotos(e, currentPage + 1);
              }}
            >
              Next Page
            </Button>
            {currentPage} / {apiData.page_count}
          </>
        )}
      </div>
      {selectedPic && (
        <>
          <img
            className="openverse-image"
            alt={`${selectedPic.title} by ${selectedPic.provider} - ${selectedPic.license}`}
            src={selectedPic.url}
            width="50%"
            height="50%"
            onClick={() => {
              setSelectedPic(null);
            }}
          ></img>
          <p>{selectedPic.tags}</p>
        </>
      )}
    </div>
  );
}

//I am not familiar with the API so I am keeping this here for now.
/**
category: null
creator: "Unknown author"
detail_url: "http://api.openverse.engineering/v1/images/0aff3595-8168-440b-83ff-7a80b65dea42/?format=json"
foreign_landing_url: "https://commons.wikimedia.org/w/index.php?curid=721264"
id: "0aff3595-8168-440b-83ff-7a80b65dea42"
license: "cc0"
license_url: "https://creativecommons.org/publicdomain/zero/1.0/deed.en"
license_version: "1.0"
provider: "wikimedia"
related_url: "http://api.openverse.engineering/v1/images/0aff3595-8168-440b-83ff-7a80b65dea42/related/?format=json"
source: "wikimedia"
thumbnail: "http://api.openverse.engineering/v1/images/0aff3595-8168-440b-83ff-7a80b65dea42/thumb/?format=json"
title: "File:Open book 01.svg"
url: "https://upload.wikimedia.org/wikipedia/co
 */
