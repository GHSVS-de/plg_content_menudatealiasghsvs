#!/usr/bin/env node
const path = require('path');

/* Configure START */
const pathBuildKram = path.resolve("../buildKramGhsvs");
const updateXml = `${pathBuildKram}/build/update_no-changelog.xml`;
// const changelogXml = `${pathBuildKram}/build/changelog.xml`;
const releaseTxt = `${pathBuildKram}/build/release_no-changelog.txt`;
/* Configure END */

const replaceXml = require(`${pathBuildKram}/build/replaceXml.js`);
const helper = require(`${pathBuildKram}/build/helper.js`);

//const fse = require(`${pathBuildKram}/node_modules/fs-extra`);
const pc = require(`${pathBuildKram}/node_modules/picocolors`);

let {
	name,
	filename,
	version,
	versionSub
} = require("./package.json");

const manifestFileName = `${filename}.xml`;
const Manifest = `${__dirname}/package/${manifestFileName}`;
// let versionSub = '';

let replaceXmlOptions = {
	"xmlFile": "",
	"zipFilename": "",
	"checksum": "",
	"dirname": __dirname,
	"jsonString": "",
	"versionSub": versionSub
};
let zipOptions = {};
let from = "";
let to = "";

(async function exec()
{
	let cleanOuts = [
		`./package`,
		`./dist`
	];

	await helper.cleanOut(cleanOuts);

	from = `./src`;
	to = `./package`;
	await helper.copy(from, to)

	await helper.mkdir('./dist');

	const zipFilename = `${name}-${version}.zip`;

	replaceXmlOptions = {...replaceXmlOptions,
		"xmlFile": Manifest,
		"zipFilename": zipFilename
	};

	await replaceXml.main(replaceXmlOptions);
	await helper.copy(`${Manifest}`, `./dist/${manifestFileName}`)

	// Create zip file and detect checksum then.
	const zipFilePath = path.resolve(`./dist/${zipFilename}`);

	zipOptions = {
		"source": path.resolve("package"),
		"target": zipFilePath
	};
	await helper.zip(zipOptions)

	replaceXmlOptions.checksum = await helper._getChecksum(zipFilePath);

	// Bei diesen werden zuerst Vorlagen nach dist/ kopiert und dort erst "replaced".
	for (const file of [updateXml, releaseTxt])
	{
		from = file;
		to = `./dist/${path.win32.basename(file)}`;
		await helper.copy(from, to)

		replaceXmlOptions.xmlFile = path.resolve(to);
		await replaceXml.main(replaceXmlOptions);
	}

	cleanOuts = [
		`./package`,
	];
	await helper.cleanOut(cleanOuts).then(
		answer => console.log(pc.cyan(pc.bold(pc.bgRed(
			`Finished. Good bye!`))))
	);
})();
