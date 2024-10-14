LfEduSharingResource

## Installation und Konfiguration des Plugins 'LfEduSharingResource'

- Kopieren Sie Dateien und Verzeichnisse in das ILIAS-Customizing-Verzeichnis nach: Customizing/global/plugins/Services/Repository/RepositoryObject/LfEduSharingResource. Erstellen Sie die fehlenden Verzeichnisse und nutzen Sie am besten git zum Holen und Aktualisieren der Dateien. Beachten Sie, dass es für verschiedene ILIAS-Versionen gegebenenfalls angepasste Plugin Versionen gibt. Unterschiedliche Versionen finden Sie in der Regel unter 'branches'.
- Führen Sie auf Kommandozeile im ILIAS-Stammverzeichnis folgendes aus: composer dump-autoload
- Öffnen Sie in ILIAS Administration > Plugins.
- Klicken Sie beim Plugin 'LfEduSharingResource' auf 'Aktionen' und dann auf 'Installieren'.
- Klicken Sie dann an gleicher Stelle auf 'Aktivieren'.
- Führen Sie gegebenenfalls insbesondere bei neuen Versionen die Option 'Sprachen neu laden' aus.
- Wählen Sie dann 'Konfigurieren'.
- Klicken Sie bei der Konfiguration auf 'Mit Heimat-Repositorium verbinden'.
  Geben Sie den 'endpoint' ein und klicken Sie auf 'Metadaten importieren'.
  Unter 'Einstellungen' können Sie dann weitere Einstellungen vornehmen.
  Wählen Sie einen eindeutigen 'Key' unter 'Authentication properties' (z.B. email). Mit diesem Key wird der Benutzer bei edu-sharing angemeldet. Weitere Ausführungen finden Sie im übernächsten Abschnitt.

Nach Aktivierung und Konfiguration sollten Sie sich den Rechten zuwenden. Damit z.B. in neu erstellten Kursen edu-sharing-Ressourcen genutzt werden können, ändern Sie die Rollenvorlagen für Kursadministratoren, Kurstutoren und Kursmitglieder. Passen Sie die Rechte für 'edu-sharing-Ressource' und 'edu-sharing-Ressource erstellen' an.

Danach steht Benutzern mit den entsprechend erteilten Rechten bei Klick auf 'Neues Objekt hinzufügen' die Option 'edu-sharing-Ressource' zur Verfügung.

## Änderung des Objekt-Icons

Oft soll das Icon für ILIAS-Objekt 'edu-sharing-Ressource' geändert werden. Das Icon heißt icon_xesr.svg und liegt in Customizing/global/plugins/Services/Repository/RepositoryObject/LfEduSharingResource/templates/ . Ersetzen Sie bei Bedarf das Icon durch eine svg-Datei mit gleichem Dateinamen.  

## Hinweise zur Übermittlung personenbezogener Daten zur Identifizierung von Nutzern

Sollen ILIAS-Benutzer auf einfache Weise ihre Objekte in edu-sharing verwalten und neue erzeugen können, bedarf es einer Zuordnung des ILIAS-Accounts zum edu-sharing-Account um den Benutzer identifizieren zu können. Damit werden in mehr oder minder großem Umfang Daten aus ILIAS an edu-sharing übermittelt. Das soll im Folgenden erörtert werden. 
Vorab ist jedoch anzumerken, dass auch unterschiedliche Accounts in ILIAS und edu-sharing genutzt werden können - und dies aus Gründen des Datenschutzes geboten sein kann. Am Einfachsten nutzt man hierbei den Gastzugang, was auch der restriktivsten Einstellung hinsichtlich der Datenweitergabe entspricht.

### Gastzugang für alle

Aktivieren Sie die Option 'Gastzugang für alle' wenn die (gemeinsame) Authentifizierung mit einem zentralen edu-sharing-Repositorium noch nicht geklärt ist oder Datenschutzerfordernisse die Übermittlung von z.B. einer UserID oder eines Nachnamens zum edu-sharing-Repositorium nicht erlauben. ILIAS-Benutzer mit der globalen Rolle 'Anonymous' erhalten stets nur einen Gastzugang für das edu-sharing-Repositorium.
Es wird beim Gastzugang stets eine für alle einheitliche Identifikation - die guest_id - übertragen. Standardmäßig lautet diese: 'esguest'. Sie können auch diese ID über die Konfigurationsoptionen des Plugins ändern. So ist es möglich, verschiedenen ILIAS-Installationen unterschiedliche IDs zuzuweisen.

### Identifizierung von Nutzern - Optionen und ihre Relevanz für den Datenschutz

In vielen Fällen ist eine weitergehende Identifizierung von Nutzern aber gewünscht - und sei es nur, um zu wissen, wieviele Personen einer Institution edu-sharing nutzen. Oder um zusätzliche Funktionalitäten wie eigene Sammlungen zu ermöglichen. Mehrere Optionen sind beim ILIAS-Plugin implementiert:
1. Benutzername
Es ist der Benutzername wie er auch für die Anmeldung bei ILIAS genutzt wird. Je nach ILIAS-Einstellungen kann dieser Benutzername jedoch geändert werden. Deshalb und insbesondere aus Datenschutzgründen wird diese Einstellung nicht empfohlen.
2. User-Id
Genutzt wird die interne, numerische ILIAS-User-Id. Diese bleibt auch bei Namenswechsel erhalten. Unter bestimmten Umständen kann jedoch von der numerischen User-Id auf konkrete Nutzer geschlossen werden. Aus Gründen des Datenschutzes kann diese Option nur eingeschränkt empfohlen werden.
3. E-Mail
Hier wird die in ILIAS hinterlegte erste E-Mail-Adresse eines Benutzers übertragen. In ILIAS können jedoch unter Umständen mehrere Nutzer die gleiche E-Mail-Adresse haben, weshalb eine eindeutige Identifikation nicht gegeben ist. Auch die Änderung einer E-Mail-Adresse kann die Identifizierung tangieren. Insbesondere aus Datenschutzgründen wird diese Einstellung nicht empfohlen.
4. Vor- und Nachname des Benutzers
Auch können Änderungen durch Namenswechsel die Identifizierung beeinträchtigen. Insbesondere aus Datenschutzgründen wird diese Einstellung nicht empfohlen.
5. Kombination aus User-Id, URL und Mandant
Hier wird eine Kombination aus User-Id mit Adresse (URL) der ILIAS-Installation und Angabe des Mandanten übertragen. Die eindeutige Identifizierung ist auch bei mehreren ILIAS-Installationen gegeben. Die Übermittlung der User-Id kann aus Gründen des Datenschutzes nur eingeschränkt empfohlen werden.
6. Shibboleth-UId
Dies setzt ein ILIAS und edusharing übergreifendes Identity-Management unter Verwendung von Shibboleth voraus. Die eindeutige Identifizierung eines Nutzers ist gegeben. Datenschutzerwägungen etwa bei entstehenden Nutzerprofilen sind zu beachten.
7. ZOERR-Authentifizierung
Diese auf Shibboleth aufbauende Authentifizierung ist für Hochschulen aus Baden-Württemberg vorgesehen. Hier wird ein zusätzliches Merkmal zur Identizierung eines Nutzers genutzt ('ZOERR_Auth').
8. Zufalls-ID kombiniert mit einer eindeutigen ILIAS-Plattform-ID, die als E-Mail-Adresse formatiert ist.
Pro ILIAS-Benutzer wird eine eindeutige 32-stellige Zufalls-ID erzeugt. Diese Zufalls-ID wird in ILIAS in einer Plugin-spezifischen Zuordnungstabelle gespeichert. Die Zufalls-ID bleibt bei jedem Aufruf identisch. Ergänzt wird diese Zufalls-Id durch '@', gefolgt von einer eindeutigen ILIAS-Plattform-ID und dem Zusatz '.ilias'. Es wird somit eine E-Mail-Adresse simuliert. In edu-sharing kann somit erfasst werden, dass es einen eindeutigen Nutzer gibt, ohne dass die Zuordnung zu einem konkreten Nutzer erfolgen kann. Es sind somit pseudonymisierte Daten. Bei Wegfall oder Nicht-Zugreifbarkeit der ILIAS-Zuordnungstabelle könnte man bei edu-sharing von anonymen Daten sprechen.

Abgesehen von der letzten Option können für die zuvor genannten Identifizierungsmöglichkeiten zusätzliche Authentifizierungsinformationen übermittelt werden. Ist diese Option in der Plugin-Konfiguration aktiviert, werden bei der Anfrage Vor- und Nachname sowie die E-Mail-Adresse übermittelt. Beachten Sie hierbei Datenschutzerfordernisse. 

FAU ___

### Installation 

```
mkdir -p Customizing/global/plugins/Services/Repository/RepositoryObject
cd Customizing/global/plugins/Services/Repository/RepositoryObject
git clone ...



