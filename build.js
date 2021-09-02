const fse = require('fs-extra');
const util = require("util");
const rimRaf = util.promisify(require("rimraf"));
const chalk = require('chalk');
const path = require("path");
const replaceXml = require('./build/replaceXml.js');
const helper = require('./build/helper.js');

const {
	name,
	filename,
	version,
} = require("./package.json");

const manifestFileName = `${filename}.xml`;
const Manifest = `${__dirname}/package/${manifestFileName}`;
const source = `./node_modules/hyphenopoly`;
const target = `./media/js/hyphenopoly`;
let versionSub = '';

(async function exec()
{
	let cleanOuts = [
		`./package`,
		`./dist`,
		target
	];

	await helper.cleanOut(cleanOuts);

	versionSub = await helper.findVersionSub (
		path.join(__dirname, source, `package.json`),
		'Hyphenopoly');

	console.log(chalk.magentaBright(`versionSub identified as: "${versionSub}"`));

	await fse.copy(`./${source}/Hyphenopoly.js`,
		`./${target}/-uncompressed/Hyphenopoly.js`
	).then(
		answer => console.log(chalk.yellowBright(
			`Copied unminified "Hyphenopoly.js" into folder "${target}/-uncompressed/".`))
	);

	await fse.copy(`./${source}/Hyphenopoly_Loader.js`,
		`./${target}/-uncompressed/Hyphenopoly_Loader.js`
	).then(
		answer => console.log(chalk.yellowBright(
			`Copied unminified "Hyphenopoly_Loader.js" into folder "${target}/-uncompressed/".`))
	);

	await fse.copy(`./${source}/min`, `${target}`
	).then(
		answer => console.log(chalk.yellowBright(
			`Copied minified JS files and patterns into "${target}".`))
	);

	await fse.copy(`./${source}/LICENSE`, `./${target}/LICENSE.txt`
	).then(
		answer => console.log(chalk.yellowBright(
			`Copied "LICENSE" as "${target}/LICENSE.txt".`))
	);

	await fse.copy(`./${source}/LICENSE`,	`./src/LICENSE_Hyphenopoly.txt`
	).then(
		answer => console.log(chalk.yellowBright(
			`Copied "LICENSE" as "./src/LICENSE_Hyphenopoly.txt".`))
	);

	await fse.copy("./src", "./package"
	).then(
		answer => console.log(chalk.yellowBright(`Copied "./src" to "./package".`))
	);

	await fse.copy("./media", "./package/media"
	).then(
		answer => console.log(chalk.yellowBright(`Copied "./media" to "./package".`))
	);

	if (!(await fse.exists("./dist")))
	{
    	await fse.mkdir("./dist"
		).then(
			answer => console.log(chalk.yellowBright(`Created "./dist".`))
		);
  }

	const zipFilename = `${name}-${version}_${versionSub}.zip`;

	await replaceXml.main(Manifest, zipFilename);
	await fse.copy(`${Manifest}`, `./dist/${manifestFileName}`).then(
		answer => console.log(chalk.yellowBright(
			`Copied "${manifestFileName}" to "./dist".`))
	);

	// Create zip file and detect checksum then.
	const zipFilePath = `./dist/${zipFilename}`;

	const zip = new (require('adm-zip'))();
	zip.addLocalFolder("package", false);
	await zip.writeZip(`${zipFilePath}`);
	console.log(chalk.cyanBright(chalk.bgRed(
		`"./dist/${zipFilename}" written.`)));

	const Digest = 'sha256'; //sha384, sha512
	const checksum = await helper.getChecksum(zipFilePath, Digest)
  .then(
		hash => {
			const tag = `<${Digest}>${hash}</${Digest}>`;
			console.log(chalk.greenBright(`Checksum tag is: ${tag}`));
			return tag;
		}
	)
	.catch(error => {
		console.log(error);
		console.log(chalk.redBright(`Error while checksum creation. I won't set one!`));
		return '';
	});

	let xmlFile = 'update.xml';
	await fse.copy(`./${xmlFile}`, `./dist/${xmlFile}`).then(
		answer => console.log(chalk.yellowBright(
			`Copied "${xmlFile}" to ./dist.`))
	);
	await replaceXml.main(`${__dirname}/dist/${xmlFile}`, zipFilename, checksum);

	xmlFile = 'changelog.xml';
	await fse.copy(`./${xmlFile}`, `./dist/${xmlFile}`).then(
		answer => console.log(chalk.yellowBright(
			`Copied "${xmlFile}" to ./dist.`))
	);
	await replaceXml.main(`${__dirname}/dist/${xmlFile}`, zipFilename, checksum);

	xmlFile = 'release.txt';
	await fse.copy(`./${xmlFile}`, `./dist/${xmlFile}`).then(
		answer => console.log(chalk.yellowBright(
			`Copied "${xmlFile}" to ./dist.`))
	);
	await replaceXml.main(`${__dirname}/dist/${xmlFile}`, zipFilename, checksum);

	cleanOuts = [
		`./package`,
		target
	];
	await helper.cleanOut(cleanOuts).then(
		answer => console.log(chalk.cyanBright(chalk.bgRed(
			`Finished. Good bye!`)))
	);
})();
