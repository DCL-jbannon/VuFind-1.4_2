package org.vufind;

import java.io.File;
import java.io.FileReader;

import org.ini4j.Ini;
import org.ini4j.Profile.Section;

public class TestUtil {
	public static Ini loadConfigFile(String filename, String serverName)
			throws Exception {
		// First load the default config file
		String configName = "../../sites/default/conf/" + filename;
		File configFile = new File(configName);

		// Parse the configuration file
		Ini ini = new Ini();
		ini.load(new FileReader(configFile));

		// Now override with the site specific configuration
		String siteSpecificFilename = "../../sites/" + serverName + "/conf/"
				+ filename;
		File siteSpecificFile = new File(siteSpecificFilename);

		Ini siteSpecificIni = new Ini();
		siteSpecificIni.load(new FileReader(siteSpecificFile));
		for (Section curSection : siteSpecificIni.values()) {
			for (String curKey : curSection.keySet()) {
				ini.put(curSection.getName(), curKey, curSection.get(curKey));
			}
		}
		return ini;
	}
}
