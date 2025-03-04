${SegmentFile}

${Segment.OnInit}
	; Borrowed the following from PAL 2.2, Remove on release of PAL 2.2
		; Work out if it's 64-bit or 32-bit
	System::Call kernel32::GetCurrentProcess()i.s
	System::Call kernel32::IsWow64Process(is,*i.r0)
	${If} $0 == 0
		StrCpy $Bits 32
		Rename "$EXEDIR\App\Capture2Text64\tessdata" "$EXEDIR\App\Capture2Text\tessdata"
	${Else}
		StrCpy $Bits 64
		Rename "$EXEDIR\App\Capture2Text\tessdata" "$EXEDIR\App\Capture2Text64\tessdata"
	${EndIf}
!macroend

${SegmentInit}
    ${If} $Bits = 64
        ${SetEnvironmentVariablesPath} FullAppDir "$EXEDIR\App\Capture2Text64"
	${Else}
        ${SetEnvironmentVariablesPath} FullAppDir "$EXEDIR\App\Capture2Text"
	${EndIf}
!macroend
